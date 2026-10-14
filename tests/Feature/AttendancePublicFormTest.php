<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendancePublicFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_status_hanya_menjawab_karyawan_yang_diminta(): void
    {
        $a = Employee::create(['name' => 'Karyawan A', 'position' => 'Operator']);
        $b = Employee::create(['name' => 'Karyawan B', 'position' => 'Operator']);
        $this->absenMasuk($a->id, 'fp-a');

        $responB = $this->getJson("/attendance/check-status?employee_id={$b->id}&device_fingerprint=fp-b");
        $responB->assertOk()->assertExactJson(['status' => 'can_clock_in']);

        $responA = $this->getJson("/attendance/check-status?employee_id={$a->id}&device_fingerprint=fp-a");
        $responA->assertOk()->assertExactJson(['status' => 'can_clock_out']);

        // Tidak ada nama atau ID karyawan lain yang bocor
        $this->assertStringNotContainsString('Karyawan A', $responB->getContent());
        $this->assertStringNotContainsString('Karyawan B', $responA->getContent());
    }

    public function test_check_status_id_tidak_terdaftar_dijawab_no_employee(): void
    {
        $this->getJson('/attendance/check-status?employee_id=99999')
            ->assertOk()->assertExactJson(['status' => 'no_employee']);
        $this->getJson('/attendance/check-status?employee_id=abc')
            ->assertOk()->assertExactJson(['status' => 'no_employee']);
    }

    public function test_perangkat_yang_dipakai_karyawan_lain_diblokir_tanpa_menyebut_siapa(): void
    {
        $a = Employee::create(['name' => 'Karyawan A', 'position' => 'Operator']);
        $b = Employee::create(['name' => 'Karyawan B', 'position' => 'Operator']);
        $this->absenMasuk($a->id, 'fp-bersama');

        $respon = $this->getJson("/attendance/check-status?employee_id={$b->id}&device_fingerprint=fp-bersama");
        $respon->assertOk()->assertJson(['status' => 'device_blocked']);
        $this->assertSame(['status', 'message'], array_keys($respon->json()));
        $this->assertStringNotContainsString('Karyawan A', $respon->getContent());
    }

    public function test_rate_limit_per_ip_membatasi_submit_dengan_employee_id_berbeda(): void
    {
        $karyawan = collect(range(1, 31))
            ->map(fn ($i) => Employee::create(['name' => "Karyawan {$i}", 'position' => 'Operator']));

        $status = $karyawan->map(fn ($k) => $this->post('/attendance', [
            'type' => 'clock_in', 'employee_id' => $k->id, 'project_id' => 1,
            'presence_status' => 'Sakit', 'device_fingerprint' => "fp-{$k->id}",
        ])->getStatusCode());

        // 30 pertama lolos limit IP, ke-31 ditolak (429) walaupun employee_id berbeda
        $this->assertSame(30, $status->take(30)->filter(fn ($s) => $s === 302)->count());
        $this->assertSame(429, $status->last());
    }

    public function test_rate_limit_per_karyawan_tetap_berlaku(): void
    {
        $k = Employee::create(['name' => 'Karyawan A', 'position' => 'Operator']);
        $data = ['type' => 'clock_in', 'employee_id' => $k->id, 'project_id' => 1,
            'presence_status' => 'Sakit', 'device_fingerprint' => 'fp-a'];

        $this->post('/attendance', $data);
        $this->post('/attendance', $data);
        $this->post('/attendance', $data);
        $this->post('/attendance', $data)->assertStatus(429);
    }

    private function absenMasuk(int $employeeId, string $fingerprint): void
    {
        Attendance::create([
            'attendance_code' => 'UJI-' . $employeeId,
            'type' => 'clock_in',
            'employee_id' => $employeeId,
            'project_id' => 1,
            'presence_status' => 'Hadir',
            'blood_pressure' => '—',
            'spo2' => 0,
            'temperature' => 0,
            'tak' => true,
            'fit_status' => 'Fit',
            'device_fingerprint' => $fingerprint,
        ]);
    }
}
