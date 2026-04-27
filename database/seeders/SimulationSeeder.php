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
use Carbon\Carbon;

class SimulationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Create 500 Employees
        $employees = [];
        for ($i = 0; $i < 500; $i++) {
            $employees[] = Employee::create([
                'name' => $faker->name,
                'position' => $faker->randomElement(['Operator', 'Mekanik', 'Driver', 'Foreman', 'Supervisor']),
            ]);
        }

        // Create 500 Units
        $units = [];
        for ($i = 0; $i < 500; $i++) {
            $units[] = Unit::create([
                'no_kendaraan' => 'DT-' . $faker->unique()->numberBetween(10000, 99999),
                'jenis_alat' => $faker->randomElement(['Dump Truck', 'Excavator', 'Dozer', 'Grader']),
                'ct' => $faker->word,
            ]);
        }

        $projects = Project::all();

        // Create 500 Attendances for Today
        $todayPrefix = 'FitToWork-TMJ-' . now()->format('dmy');
        foreach ($employees as $index => $emp) {
            $code = $todayPrefix . '-' . Str::uuid()->toString();
            
            Attendance::create([
                'attendance_code' => $code,
                'employee_id' => $emp->id,
                'project_id' => $projects->random()->id,
                'presence_status' => $faker->randomElement(['Hadir', 'Hadir', 'Hadir', 'Izin', 'Cuti', 'Tidak Hadir']),
                'blood_pressure' => $faker->numberBetween(110, 130) . '/' . $faker->numberBetween(70, 90),
                'spo2' => $faker->numberBetween(95, 100),
                'temperature' => $faker->randomFloat(1, 36.0, 37.5),
                'tak' => $faker->boolean(90),
                'fit_status' => $faker->randomElement(['Fit', 'Fit', 'Fit', 'Unfit']),
                'created_at' => Carbon::today()->addHours(rand(6, 9))->addMinutes(rand(0, 59)),
                'updated_at' => now(),
            ]);
        }

        // Create 500 Unit Statuses
        for ($i = 0; $i < 500; $i++) {
            $status = $faker->randomElement(['Ready', 'Ready', 'Standby', 'Down']);
            UnitStatus::create([
                'unit_id' => $faker->randomElement($units)->id,
                'operator_id' => $faker->randomElement($employees)->id,
                'project_id' => $projects->random()->id,
                'status' => $status,
                'location' => $faker->city,
                'damage_type' => $status === 'Down' ? $faker->sentence : null,
                'hm' => $faker->randomFloat(1, 1000, 5000),
                'km' => $faker->randomFloat(1, 10000, 50000),
                'created_at' => Carbon::today()->addHours(rand(6, 17))->addMinutes(rand(0, 59)),
                'updated_at' => now(),
            ]);
        }
    }
}
