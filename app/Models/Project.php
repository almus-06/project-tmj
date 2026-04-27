<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'location'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function unitStatuses()
    {
        return $this->hasMany(UnitStatus::class);
    }
}
