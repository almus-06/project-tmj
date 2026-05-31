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
        $this->command->info('Creating sample verification photo...');
        
        // Ensure storage directory exists
        Storage::disk('public')->makeDirectory('attendance_photos');
        
        // 1x1 transparent PNG base64
        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $imageData = base64_decode($pngBase64);
        
        // Save placeholder photo
        $placeholderPath = 'attendance_photos/placeholder.png';
        Storage::disk('public')->put($placeholderPath, $imageData);
        
        $employees = Employee::whereIn('id', [117, 134])->get();
        if ($employees->isEmpty()) {
            $this->command->error('Target employees (117, 134) not found.');
            return;
        }

        $projects = Project::all();
        if ($projects->isEmpty()) {
            $this->command->error('No projects found to associate attendance.');
            return;
        }

        $this->command->info('Generating 100 attendance records per employee...');

        foreach ($employees as $employee) {
            $this->command->info("Seeding for employee: {$employee->name} (ID: {$employee->id})");
            
            // Generate for the last 120 days, aiming for ~100 active days (excluding most Sundays)
            $recordsCount = 0;
            $currentDate = Carbon::now()->subDays(120);
            $endDate = Carbon::now();

            while ($currentDate->lte($endDate) && $recordsCount < 100) {
                // Skip most Sundays (simulation of day off)
                if ($currentDate->dayOfWeek === Carbon::SUNDAY && rand(1, 10) > 2) {
                    $currentDate->addDay();
                    continue;
                }

                // Random presence status
                $randVal = rand(1, 100);
                if ($randVal <= 88) {
                    $presenceStatus = 'Hadir';
                } elseif ($randVal <= 93) {
                    $presenceStatus = 'Sakit';
                } else {
                    $presenceStatus = 'Izin';
                }

                $project = $projects->random();
                
                // Determine shifts
                $shift = rand(1, 10) > 2 ? 'Shift Pagi' : 'Shift Malam';
                
                // Health metrics
                if ($presenceStatus === 'Hadir') {
                    // Mostly healthy, occasionally unfit
                    $isHealthy = rand(1, 100) > 3;
                    if ($isHealthy) {
                        $temp = number_format(rand(360, 372) / 10, 2);
                        $spo2 = rand(96, 100);
                        $bpSystolic = rand(110, 125);
                        $bpDiastolic = rand(70, 85);
                        $fitStatus = 'Fit';
                    } else {
                        // Unfit case
                        $temp = number_format(rand(378, 389) / 10, 2);
                        $spo2 = rand(90, 94);
                        $bpSystolic = rand(130, 145);
                        $bpDiastolic = rand(88, 98);
                        $fitStatus = 'Unfit';
                    }
                    $bloodPressure = "{$bpSystolic}/{$bpDiastolic}";
                    $tak = rand(0, 10) > 8 ? 1 : 0; // Tanda Awal Kelelahan
                } else {
                    // Sick/Permit status
                    $temp = 0.00;
                    $spo2 = 0;
                    $bloodPressure = '0/0';
                    $tak = 0;
                    $fitStatus = 'Unfit';
                }

                // Geolocation
                $latOffset = (rand(-50, 50) / 100000);
                $lonOffset = (rand(-50, 50) / 100000);
                $latitude = ($project->latitude ?? -2.62330530) + $latOffset;
                $longitude = ($project->longitude ?? 121.36963140) + $lonOffset;
                
                // 5% of the time, make distance outside radius
                $isOutside = rand(1, 100) <= 5;
                if ($isOutside && $presenceStatus === 'Hadir') {
                    $latitude += 0.005; // ~500 meters away
                    $longitude += 0.005;
                    $distance = rand(500, 800);
                    $isInsideRadius = false;
                } else {
                    $distance = rand(2, 18);
                    $isInsideRadius = true;
                }

                // Accuracy and GPS details
                $accuracy = number_format(rand(30, 120) / 10, 1);
                $altitude = rand(120, 280);
                $heading = rand(0, 359);
                $speed = rand(0, 10) > 8 ? number_format(rand(5, 12) / 10, 2) : 0.00;

                // Device info
                $devices = ['Samsung Galaxy A54', 'Xiaomi Redmi Note 12', 'Oppo Reno 10', 'iPhone 13', 'Vivo V29'];
                $deviceInfo = $devices[array_rand($devices)];
                
                // Suspect fake GPS 2% of the time
                $isFakeGps = rand(1, 100) <= 2;

                // Create attendance code
                $datePrefix = $currentDate->format('dmy');
                $randomSeq = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $attendanceCode = "FitToWork-TMJ-{$datePrefix}-{$randomSeq}";

                // Create record
                Attendance::create([
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
                    'created_at' => $currentDate->copy()->setHour(rand(6, 8))->setMinute(rand(0, 59))->setSecond(rand(0, 59)),
                    'updated_at' => $currentDate,
                ]);

                $recordsCount++;
                $currentDate->addDay();
            }
        }

        $this->command->info('Seeding attendance history successfully completed!');
    }
}
