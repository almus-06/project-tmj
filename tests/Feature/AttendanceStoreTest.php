<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_dua_submit_berurutan_mendapat_kode_berbeda(): void
    {
        $a = Employee::create(['name' => 'Karyawan A', 'position' => 'Operator']);
        $b = Employee::create(['name' => 'Karyawan B', 'position' => 'Operator']);

        $this->post('/attendance', $this->data($a->id, 'fp-a'))->assertRedirect(route('attendance.success'));
        $this->post('/attendance', $this->data($b->id, 'fp-b'))->assertRedirect(route('attendance.success'));

        $kode = Attendance::orderBy('id')->pluck('attendance_code')->all();
        $prefix = 'FitToWork-TMJ-' . now()->format('dmy');

        $this->assertSame([$prefix . '-0001', $prefix . '-0002'], $kode);
    }

    public function test_karyawan_yang_sama_tidak_bisa_clock_in_dua_kali(): void
    {
        $a = Employee::create(['name' => 'Karyawan A', 'position' => 'Operator']);

        $this->post('/attendance', $this->data($a->id, 'fp-a'))->assertRedirect(route('attendance.success'));
        $this->post('/attendance', $this->data($a->id, 'fp-a'))->assertSessionHasErrors('employee_id');

        $this->assertSame(1, Attendance::count());
    }

    /** Data absensi "Sakit" agar tidak memerlukan foto. */
    private function data(int $employeeId, string $fingerprint): array
    {
        return [
            'type' => 'clock_in',
            'employee_id' => $employeeId,
            'project_id' => 1,
            'presence_status' => 'Sakit',
            'device_fingerprint' => $fingerprint,
        ];
    }
}
