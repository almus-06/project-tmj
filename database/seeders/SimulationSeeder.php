<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Unit;
use App\Models\Attendance;
use App\Models\UnitStatus;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SimulationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Bersihkan data lama agar simulasi 1 tahun ini akurat
        Schema::disableForeignKeyConstraints();
        Attendance::truncate();
        UnitStatus::truncate();
        Schema::enableForeignKeyConstraints();

        $employees = Employee::all();
        $units = Unit::all();
        $projects = Project::all();

        if ($employees->isEmpty()) {
            $this->command->error('Data Karyawan kosong!');
            return;
        }

        $totalDays = 365;
        $this->command->info("Memulai simulasi $totalDays hari (1 tahun) untuk " . $employees->count() . " karyawan...");

        $hmList = ['exca', 'grader', 'compactor', 'dozer'];

        // Loop per hari
        for ($i = $totalDays; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Info progres setiap 30 hari
            if ($i % 30 == 0) {
                $this->command->info("Memproses data sisa: $i hari lagi...");
            }

            $attendanceData = [];
            $fleetData = [];

            foreach ($employees as $emp) {
                $time = (clone $date)->addHours(rand(6, 8))->addMinutes(rand(0, 59));
                
                $attendanceData[] = [
                    'attendance_code' => 'FTW-' . $date->format('ymd') . '-' . strtoupper(Str::random(6)),
                    'employee_id' => $emp->id,
                    'project_id' => $projects->random()->id,
                    'presence_status' => $faker->randomElement(['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin', 'Cuti']),
                    'blood_pressure' => rand(110, 130) . '/' . rand(70, 85),
                    'spo2' => rand(97, 100),
                    'temperature' => $faker->randomFloat(1, 36.1, 37.2),
                    'tak' => true,
                    'fit_status' => 'Fit',
                    'created_at' => $time,
                    'updated_at' => $time,
                ];

                $isOperator = Str::contains(strtolower($emp->position), ['op', 'operator', 'driver']);
                if ($isOperator && rand(1, 10) > 3) {
                    $unit = $units->random();
                    $isHm = false;
                    foreach($hmList as $h) { if(stripos($unit->jenis_alat, $h) !== false) $isHm = true; }

                    $fleetData[] = [
                        'unit_id' => $unit->id,
                        'operator_id' => $emp->id,
                        'project_id' => $projects->random()->id,
                        'status' => $faker->randomElement(['Ready', 'Ready', 'Standby']),
                        'location' => 'PIT AREA ' . $faker->randomElement(['A', 'B', 'C']),
                        'hm' => $isHm ? rand(1000, 9999) : 0,
                        'km' => !$isHm ? rand(10000, 99999) : 0,
                        'created_at' => (clone $time)->addMinutes(rand(10, 30)),
                        'updated_at' => (clone $time)->addMinutes(rand(10, 30)),
                    ];
                }
            }

            // Bulk Insert
            DB::table('attendances')->insert($attendanceData);
            if (!empty($fleetData)) {
                DB::table('unit_statuses')->insert($fleetData);
            }
        }

        $this->command->info('Simulasi 1 tahun selesai!');
        $this->command->info('Total Record Absensi: ' . Attendance::count());
        $this->command->info('Total Record Fleet: ' . UnitStatus::count());
    }
}
