<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;
use App\Services\AttendanceCodeGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Data absensi DUMMY untuk mencoba fitur dashboard/export di lokal.
 *
 * Hanya MENAMBAH baris ke tabel attendances (tidak truncate, tidak
 * mengubah karyawan/project) dan menolak berjalan di production.
 *
 * Pakai: php artisan db:seed --class=DummyAttendanceSeeder
 * Opsi lewat env: DUMMY_KARYAWAN (default 100), DUMMY_HARI (default 5)
 */
class DummyAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('DummyAttendanceSeeder tidak boleh dijalankan di production.');
        }

        $jumlahKaryawan = (int) (env('DUMMY_KARYAWAN') ?: 100);
        $jumlahHari = (int) (env('DUMMY_HARI') ?: 5);

        $projects = Project::all();
        if ($projects->isEmpty()) {
            throw new RuntimeException('Tabel projects kosong.');
        }

        $karyawan = Employee::inRandomOrder()->limit($jumlahKaryawan)->get();
        if ($karyawan->isEmpty()) {
            throw new RuntimeException('Tabel employees kosong; jalankan SqlDataSeeder dulu.');
        }

        $generator = new AttendanceCodeGenerator;
        $dibuat = 0;

        for ($h = $jumlahHari - 1; $h >= 0; $h--) {
            $tanggal = Carbon::today()->subDays($h);

            foreach ($karyawan as $i => $emp) {
                // Sudah punya absensi di tanggal ini → lewati (idempoten)
                if (Attendance::where('employee_id', $emp->id)->whereDate('created_at', $tanggal)->exists()) {
                    continue;
                }

                // ~5% tidak absen sama sekali di hari itu
                if (mt_rand(1, 100) <= 5) {
                    continue;
                }

                $project = $projects[$i % $projects->count()];
                $fingerprint = 'dummy-' . Str::substr(md5('dev-' . $emp->id), 0, 20);

                $acak = mt_rand(1, 100);
                $status = $acak <= 90 ? 'Hadir' : ($acak <= 96 ? 'Sakit' : 'Izin');

                // Clock-in: 06:30–08:15 (shift pagi) atau 18:00–19:00 (shift malam, 20%)
                $malam = mt_rand(1, 100) <= 20;
                $jamMasuk = $malam
                    ? $tanggal->copy()->setTime(18, mt_rand(0, 59), mt_rand(0, 59))
                    : $tanggal->copy()->setTime(mt_rand(6, 7), mt_rand(0, 59), mt_rand(0, 59));
                if (! $malam && $jamMasuk->hour === 7 && $jamMasuk->minute > 15) {
                    $jamMasuk->setTime(7, mt_rand(0, 15));
                }

                $this->simpan($generator, $emp, $project, 'clock_in', $status, $jamMasuk, $fingerprint);
                $dibuat++;

                // Clock-out untuk ~85% yang Hadir, 8–10 jam kemudian
                if ($status === 'Hadir' && mt_rand(1, 100) <= 85) {
                    $jamPulang = $jamMasuk->copy()->addHours(mt_rand(8, 10))->addMinutes(mt_rand(0, 59));
                    if ($jamPulang->isFuture()) {
                        continue;
                    }
                    $this->simpan($generator, $emp, $project, 'clock_out', 'Pulang', $jamPulang, $fingerprint);
                    $dibuat++;
                }
            }
        }

        $this->command?->info("Selesai: {$dibuat} baris absensi dummy ditambahkan untuk {$karyawan->count()} karyawan, {$jumlahHari} hari terakhir.");
    }

    private function simpan(
        AttendanceCodeGenerator $generator,
        Employee $emp,
        Project $project,
        string $type,
        string $status,
        Carbon $waktu,
        string $fingerprint
    ): void {
        $data = [
            'attendance_code' => 'TMP-' . Str::uuid(),
            'type' => $type,
            'employee_id' => $emp->id,
            'project_id' => $project->id,
            'presence_status' => $status,
            'blood_pressure' => '—',
            'spo2' => 0,
            'temperature' => 0,
            'tak' => true,
            'fit_status' => 'Fit',
            'shift' => $project->tentukanShift($waktu->hour),
            'device_fingerprint' => $fingerprint,
            'device_info' => 'Dummy Android 14 / Chrome',
            'ip_address' => '10.10.' . mt_rand(0, 255) . '.' . mt_rand(1, 254),
            'is_fake_gps_suspected' => false,
            'photo_path' => null,
        ];

        // Koordinat di sekitar titik project (±~300 m); 85% di dalam radius
        if ($project->latitude !== null && $project->longitude !== null && $status !== 'Izin') {
            $diDalam = mt_rand(1, 100) <= 85;
            $jarakMeter = $diDalam
                ? mt_rand(0, max(1, (int) $project->radius_meters))
                : mt_rand((int) $project->radius_meters + 10, (int) $project->radius_meters + 300);
            $sudut = deg2rad(mt_rand(0, 359));
            $dLat = ($jarakMeter * cos($sudut)) / 111320;
            $dLng = ($jarakMeter * sin($sudut)) / (111320 * cos(deg2rad((float) $project->latitude)));

            $data['latitude'] = round((float) $project->latitude + $dLat, 8);
            $data['longitude'] = round((float) $project->longitude + $dLng, 8);
            $data['distance_from_project'] = round($jarakMeter + mt_rand(0, 99) / 100, 2);
            $data['is_inside_radius'] = $diDalam;
            $data['accuracy'] = mt_rand(400, 2500) / 100;
            $data['altitude'] = mt_rand(300, 600);
            $data['heading'] = mt_rand(0, 359);
            $data['speed'] = mt_rand(0, 150) / 100;
        }

        DB::transaction(function () use ($generator, $data, $waktu) {
            $rec = Attendance::create($data);
            $rec->forceFill([
                'attendance_code' => $generator->next($waktu),
                'created_at' => $waktu,
                'updated_at' => $waktu,
            ])->saveQuietly();
        });
    }
}
