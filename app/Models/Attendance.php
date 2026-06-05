<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;
    protected $fillable = [
        'attendance_code',
        'type',
        'employee_id',
        'project_id',
        'presence_status',
        'blood_pressure',
        'spo2',
        'temperature',
        'tak',
        'fit_status',
        'shift',
        'latitude',
        'longitude',
        'distance_from_project',
        'is_inside_radius',
        'accuracy',
        'altitude',
        'heading',
        'speed',
        'device_info',
        'device_fingerprint',
        'ip_address',
        'is_fake_gps_suspected',
        'photo_path'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'distance_from_project' => 'double',
        'is_inside_radius' => 'boolean',
        'accuracy' => 'double',
        'altitude' => 'double',
        'heading' => 'double',
        'speed' => 'double',
        'is_fake_gps_suspected' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
