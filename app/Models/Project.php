<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'location',
        'latitude',
        'longitude',
        'radius_meters',
        'shift_pagi_mulai',
        'shift_pagi_selesai',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius_meters' => 'integer',
        'shift_pagi_mulai' => 'integer',
        'shift_pagi_selesai' => 'integer',
    ];

    /**
     * Tentukan nama shift untuk jam tertentu (0–23) berdasarkan aturan
     * shift project ini. Project tanpa aturan shift mengembalikan 'Luar Shift'.
     */
    public function tentukanShift(int $jam): string
    {
        if ($this->shift_pagi_mulai === null || $this->shift_pagi_selesai === null) {
            return 'Luar Shift';
        }

        return ($jam >= $this->shift_pagi_mulai && $jam < $this->shift_pagi_selesai)
            ? 'Shift Pagi'
            : 'Shift Malam';
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function unitStatuses()
    {
        return $this->hasMany(UnitStatus::class);
    }
}
