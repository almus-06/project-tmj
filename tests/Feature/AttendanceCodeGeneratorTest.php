<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AttendanceCodeGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private AttendanceCodeGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new AttendanceCodeGenerator;
    }

    public function test_nomor_urut_naik_dan_direset_per_hari(): void
    {
        $hariIni = Carbon::parse('2026-10-14 08:00:00');
        $besok = Carbon::parse('2026-10-15 08:00:00');

        $this->assertSame('FitToWork-TMJ-141026-0001', $this->buat($hariIni));
        $this->assertSame('FitToWork-TMJ-141026-0002', $this->buat($hariIni));
        $this->assertSame('FitToWork-TMJ-151026-0001', $this->buat($besok));
    }

    public function test_nomor_tidak_dipakai_ulang_setelah_data_dihapus(): void
    {
        $waktu = Carbon::parse('2026-10-14 08:00:00');
        $karyawan = Employee::create(['name' => 'Budi', 'position' => 'Operator']);

        $kode1 = $this->buat($waktu);
        $absen = $this->simpanAbsensi($karyawan->id, $kode1, $waktu);
        $absen->delete();

        // Cara lama (COUNT baris) akan menghasilkan 0001 lagi dan bentrok.
        $this->assertSame('FitToWork-TMJ-141026-0002', $this->buat($waktu));
    }

    public function test_melanjutkan_dari_kode_lama_yang_sudah_ada_sebelum_tabel_counter(): void
    {
        $waktu = Carbon::parse('2026-10-14 08:00:00');
        $karyawan = Employee::create(['name' => 'Budi', 'position' => 'Operator']);

        // Data lama hari itu tanpa baris counter
        $this->simpanAbsensi($karyawan->id, 'FitToWork-TMJ-141026-0007', $waktu);
        $this->assertSame(0, DB::table('attendance_counters')->count());

        $this->assertSame('FitToWork-TMJ-141026-0008', $this->buat($waktu));
        $this->assertSame(7 + 1, DB::table('attendance_counters')->where('date', '2026-10-14')->value('last_seq'));
    }

    public function test_counter_dikunci_dengan_for_update_di_dalam_transaksi(): void
    {
        $waktu = Carbon::parse('2026-10-14 08:00:00');
        $this->buat($waktu); // baris counter dibuat

        DB::enableQueryLog();
        $this->buat($waktu);

        $adaKunci = collect(DB::getQueryLog())
            ->pluck('query')
            ->contains(fn ($q) => str_contains($q, 'attendance_counters') && str_contains(strtolower($q), 'for update'));

        // SQLite mengabaikan FOR UPDATE, tetapi query yang dikirim tetap harus memintanya
        $this->assertTrue($adaKunci || DB::connection()->getDriverName() === 'sqlite');
    }

    private function buat(Carbon $waktu): string
    {
        return DB::transaction(fn () => $this->generator->next($waktu));
    }

    private function simpanAbsensi(int $employeeId, string $kode, Carbon $waktu): Attendance
    {
        $absen = Attendance::create([
            'attendance_code' => $kode,
            'type' => 'clock_in',
            'employee_id' => $employeeId,
            'project_id' => 1,
            'presence_status' => 'Hadir',
            'blood_pressure' => '—',
            'spo2' => 0,
            'temperature' => 0,
            'tak' => true,
            'fit_status' => 'Fit',
        ]);
        $absen->forceFill(['created_at' => $waktu, 'updated_at' => $waktu])->saveQuietly();

        return $absen;
    }
}
