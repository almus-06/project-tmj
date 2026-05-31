<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Updating project coordinates to Luwu Timur regions...');
        
        // 1. Malili, Luwu Timur
        $project1 = Project::find(1);
        if ($project1) {
            $project1->update([
                'latitude' => -2.63930000,
                'longitude' => 121.19630000,
                'location' => 'Malili, Luwu Timur'
            ]);
        }

        // 2. Sorowako, Luwu Timur
        $project2 = Project::find(2);
        if ($project2) {
            $project2->update([
                'latitude' => -2.62470000,
                'longitude' => 121.35330000,
                'location' => 'Sorowako, Luwu Timur'
            ]);
        }

        // 3. Towuti, Luwu Timur
        $project3 = Project::find(3);
        if ($project3) {
            $project3->update([
                'latitude' => -2.76670000,
                'longitude' => 121.43330000,
                'location' => 'Towuti, Luwu Timur'
            ]);
        }

        $this->command->info('Truncating current attendance table...');
        Attendance::truncate();

        $this->command->info('Creating sample verification photo...');
        // Ensure storage directory exists
        Storage::disk('public')->makeDirectory('attendance_photos');
        
        // 1x1 transparent PNG base64
        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $imageData = base64_decode($pngBase64);
        
        // Save placeholder photo
        $placeholderPath = 'attendance_photos/placeholder.png';
        Storage::disk('public')->put($placeholderPath, $imageData);

        // Ensure we have exactly 200 employees
        $employeeCount = Employee::count();
        if ($employeeCount < 200) {
            $needed = 200 - $employeeCount;
            $this->command->info("Creating {$needed} dummy employees to reach a total of 200...");
            for ($i = 1; $i <= $needed; $i++) {
                Employee::create([
                    'name' => "Simulated Employee " . $i,
                    'position' => "OPERATOR LAPANGAN"
                ]);
            }
        }
        
        $employees = Employee::take(200)->get();
        $projects = Project::all();
        if ($projects->isEmpty()) {
            $this->command->error('No projects found to associate attendance.');
            return;
        }

        $this->command->info('Simulating 1 month (30 days) of attendance for 200 employees (~6,000 records)...');

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        $recordsCount = 0;

        // Pre-create some device names
        $devices = ['Samsung Galaxy A54', 'Xiaomi Redmi Note 12', 'Oppo Reno 10', 'iPhone 13', 'Vivo V29'];

        for ($day = 0; $day < 30; $day++) {
            $currentDate = $startDate->copy()->addDays($day);
            $this->command->info("Seeding data for date: " . $currentDate->format('Y-m-d'));

            $attendanceData = [];

            foreach ($employees as $employee) {
                // Random presence status
                $randVal = rand(1, 100);
                if ($randVal <= 90) {
                    $presenceStatus = 'Hadir';
                } elseif ($randVal <= 95) {
                    $presenceStatus = 'Sakit';
                } else {
                    $presenceStatus = 'Izin';
                }

                $project = $projects->random();
                $shift = rand(1, 10) > 2 ? 'Shift Pagi' : 'Shift Malam';
                
                // Health metrics
                if ($presenceStatus === 'Hadir') {
                    $isHealthy = rand(1, 100) > 4;
                    if ($isHealthy) {
                        $temp = number_format(rand(360, 372) / 10, 2);
                        $spo2 = rand(96, 100);
                        $bpSystolic = rand(110, 125);
                        $bpDiastolic = rand(70, 85);
                        $fitStatus = 'Fit';
                    } else {
                        $temp = number_format(rand(378, 389) / 10, 2);
                        $spo2 = rand(90, 94);
                        $bpSystolic = rand(130, 145);
                        $bpDiastolic = rand(88, 98);
                        $fitStatus = 'Unfit';
                    }
                    $bloodPressure = "{$bpSystolic}/{$bpDiastolic}";
                    $tak = rand(0, 10) > 8 ? 1 : 0;
                } else {
                    $temp = 0.00;
                    $spo2 = 0;
                    $bloodPressure = '0/0';
                    $tak = 0;
                    $fitStatus = 'Unfit';
                }

                // Geolocation coordinates (within radius most of the time)
                $latOffset = (rand(-40, 40) / 100000);
                $lonOffset = (rand(-40, 40) / 100000);
                $latitude = ($project->latitude ?? -2.62330530) + $latOffset;
                $longitude = ($project->longitude ?? 121.36963140) + $lonOffset;
                
                $isOutside = rand(1, 100) <= 5; // 5% outside radius
                if ($isOutside && $presenceStatus === 'Hadir') {
                    $latitude += 0.004;
                    $longitude += 0.004;
                    $distance = rand(450, 750);
                    $isInsideRadius = false;
                } else {
                    $distance = rand(2, 20);
                    $isInsideRadius = true;
                }

                $accuracy = number_format(rand(30, 120) / 10, 1);
                $altitude = rand(120, 280);
                $heading = rand(0, 359);
                $speed = rand(0, 10) > 8 ? number_format(rand(5, 12) / 10, 2) : 0.00;
                $deviceInfo = $devices[array_rand($devices)];
                $isFakeGps = rand(1, 100) <= 2; // 2% fake GPS

                $datePrefix = $currentDate->format('dmy');
                $attendanceCode = "FitToWork-TMJ-{$datePrefix}-" . str_pad($employee->id, 4, '0', STR_PAD_LEFT);

                // Set attendance time
                $hour = ($shift === 'Shift Pagi') ? rand(6, 8) : rand(18, 20);
                $createdAt = $currentDate->copy()->setHour($hour)->setMinute(rand(0, 59))->setSecond(rand(0, 59));

                $attendanceData[] = [
                    'attendance_code' => $attendanceCode,
                    'employee_id' => $employee->id,
                    'project_id' => $project->id,
                    'presence_status' => $presenceStatus,
                    'blood_pressure' => $bloodPressure,
                    'spo2' => $spo2,
                    'temperature' => $temp,
                    'tak' => $tak,
                    'fit_status' => $fitStatus,
                    'shift' => $shift,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'distance_from_project' => $distance,
                    'is_inside_radius' => $isInsideRadius,
                    'accuracy' => $accuracy,
                    'altitude' => $altitude,
                    'heading' => $heading,
                    'speed' => $speed,
                    'device_info' => $deviceInfo,
                    'is_fake_gps_suspected' => $isFakeGps,
                    'photo_path' => $presenceStatus === 'Hadir' ? $placeholderPath : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }

            // Chunk insert for better database performance
            foreach (array_chunk($attendanceData, 100) as $chunk) {
                Attendance::insert($chunk);
                $recordsCount += count($chunk);
            }
        }

        $this->command->info("Successfully simulated {$recordsCount} attendance entries for 1 month!");
    }
}
