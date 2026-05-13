<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'location', 'latitude', 'longitude', 'radius_meters'];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius_meters' => 'integer',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function unitStatuses()
    {
        return $this->hasMany(UnitStatus::class);
    }
}
