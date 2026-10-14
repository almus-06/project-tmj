<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ProjectShiftTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrasi_mengisi_jam_shift_project_lama_sesuai_perilaku_sebelumnya(): void
    {
        $this->assertSame([6, 16], $this->jam('Main Dev'));
        $this->assertSame([6, 16], $this->jam('Sorlim'));
        $this->assertSame([6, 18], $this->jam('Big Fleet'));
    }

    public function test_tentukan_shift_dari_kolom_project(): void
    {
        $mainDev = Project::where('name', 'Main Dev')->first();
        $this->assertSame('Shift Pagi', $mainDev->tentukanShift(6));
        $this->assertSame('Shift Pagi', $mainDev->tentukanShift(15));
        $this->assertSame('Shift Malam', $mainDev->tentukanShift(16));
        $this->assertSame('Shift Malam', $mainDev->tentukanShift(2));

        $bigFleet = Project::where('name', 'Big Fleet')->first();
        $this->assertSame('Shift Pagi', $bigFleet->tentukanShift(17));
        $this->assertSame('Shift Malam', $bigFleet->tentukanShift(18));

        $tanpaShift = Project::create(['name' => 'Project Baru']);
        $this->assertSame('Luar Shift', $tanpaShift->tentukanShift(10));
    }

    public function test_absensi_menyimpan_shift_berdasarkan_aturan_project(): void
    {
        Carbon::setTestNow('2026-10-14 17:00:00');
        $karyawan = Employee::create(['name' => 'Budi', 'position' => 'Operator']);
        $bigFleet = Project::where('name', 'Big Fleet')->first();
        $sorlim = Project::where('name', 'Sorlim')->first();

        $this->post('/attendance', $this->data($karyawan->id, $bigFleet->id, 'fp-1'))
            ->assertRedirect(route('attendance.success'));
        $this->assertSame('Shift Pagi', Attendance::latest('id')->first()->shift);

        $karyawan2 = Employee::create(['name' => 'Siti', 'position' => 'Operator']);
        $this->post('/attendance', $this->data($karyawan2->id, $sorlim->id, 'fp-2'))
            ->assertRedirect(route('attendance.success'));
        $this->assertSame('Shift Malam', Attendance::latest('id')->first()->shift);

        Carbon::setTestNow();
    }

    public function test_chart_fleet_mengikuti_daftar_project_di_database(): void
    {
        Project::create(['name' => 'Project Baru']);
        $admin = new \App\Models\User(['name' => 'Admin', 'username' => 'admin_uji', 'password' => bcrypt('password123')]);
        $admin->forceFill(['role' => 'admin'])->save();

        $response = $this->actingAs($admin)->get('/operations-dashboard/fleet-management')->assertOk();

        $this->assertSame(['Big Fleet', 'Main Dev', 'Project Baru', 'Sorlim'], $response->viewData('chartProyek')->keys()->all());

        // Dropdown filter project memakai kutip tunggal agar tidak memutus atribut x-data="..."
        $response->assertSee("{ id: 'Project Baru', name: 'Project Baru' }", false);
        $response->assertDontSee('{ id: "Project Baru"', false);

        // Halaman absensi memakai dropdown yang sama
        $this->actingAs($admin)->get('/operations-dashboard/workforce-attendance')
            ->assertOk()
            ->assertSee("{ id: 'Project Baru', name: 'Project Baru' }", false);
    }

    private function jam(string $nama): array
    {
        $p = Project::where('name', $nama)->firstOrFail();

        return [$p->shift_pagi_mulai, $p->shift_pagi_selesai];
    }

    private function data(int $employeeId, int $projectId, string $fp): array
    {
        return [
            'type' => 'clock_in', 'employee_id' => $employeeId, 'project_id' => $projectId,
            'presence_status' => 'Sakit', 'device_fingerprint' => $fp,
        ];
    }
}
