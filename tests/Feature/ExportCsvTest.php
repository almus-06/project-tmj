<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExportCsvTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_absensi_dibaca_per_chunk_dan_memuat_kolom_tipe(): void
    {
        $karyawan = Employee::create(['name' => 'Budi Export', 'position' => 'Operator']);
        $this->buatAbsensi($karyawan->id, 2500);

        DB::enableQueryLog();
        $response = $this->actingAs($this->admin())
            ->get('/operations-dashboard/workforce-attendance?export=csv');
        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $isi = $response->streamedContent();
        $baris = array_values(array_filter(explode("\n", $isi), fn ($b) => trim($b) !== ''));

        // 1 header + 2500 data
        $this->assertCount(2501, $baris);
        $this->assertStringContainsString('Tanggal;Tipe;Kode;Karyawan', $baris[0]);
        $this->assertStringContainsString(';Masuk;', $baris[1] . $baris[2]);
        $this->assertStringContainsString(';Pulang;', $isi);

        // Data dibaca bertahap (limit 1000), bukan satu query tanpa limit
        $queryAbsensi = collect(DB::getQueryLog())
            ->pluck('query')
            ->filter(fn ($q) => str_contains($q, 'from "attendances"') && str_contains($q, 'limit 1000'));
        $this->assertGreaterThanOrEqual(3, $queryAbsensi->count(), 'Export harus memakai beberapa chunk');
        $this->assertTrue($queryAbsensi->skip(1)->every(fn ($q) => str_contains($q, '"id" <')), 'Chunk berikutnya harus memakai batas id');
    }

    public function test_export_fleet_berjalan_dan_berbahasa_indonesia(): void
    {
        $karyawan = Employee::create(['name' => 'Operator Satu', 'position' => 'Operator']);
        $unit = Unit::create(['no_kendaraan' => 'DT 01', 'jenis_alat' => 'DUMP TRUCK', 'qr_code_string' => 'DT01']);
        DB::table('unit_statuses')->insert([
            'unit_id' => $unit->id, 'operator_id' => $karyawan->id, 'project_id' => 1,
            'status' => 'Ready', 'location' => 'Pit A', 'damage_type' => null, 'hm' => 1234.5, 'km' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $isi = $this->actingAs($this->admin())
            ->get('/operations-dashboard/fleet-management?export=csv')
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Tanggal;Unit;Operator;Proyek;Status;Lokasi;Kerusakan;HM;KM', $isi);
        // fputcsv membungkus nilai yang mengandung spasi dengan tanda kutip
        $this->assertStringContainsString('"DT 01 (DUMP TRUCK)";"Operator Satu";"Main Dev";Ready;"Pit A";-;1234,50;0,00', $isi);
    }

    private function buatAbsensi(int $employeeId, int $jumlah): void
    {
        $rows = [];
        for ($i = 1; $i <= $jumlah; $i++) {
            $rows[] = [
                'attendance_code' => 'UJI-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'type' => $i % 2 === 0 ? 'clock_out' : 'clock_in',
                'employee_id' => $employeeId,
                'project_id' => 1,
                'presence_status' => 'Hadir',
                'blood_pressure' => '—',
                'spo2' => 0,
                'temperature' => 0,
                'tak' => true,
                'fit_status' => 'Fit',
                'created_at' => now()->subMinutes($jumlah - $i),
                'updated_at' => now(),
            ];
            if (count($rows) === 500) {
                DB::table('attendances')->insert($rows);
                $rows = [];
            }
        }
        if ($rows) {
            DB::table('attendances')->insert($rows);
        }
    }

    private function admin(): User
    {
        $user = new User(['name' => 'Admin', 'username' => 'admin_uji', 'password' => Hash::make('password123')]);
        $user->forceFill(['role' => 'admin'])->save();

        return $user;
    }
}
