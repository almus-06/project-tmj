<?php

namespace App\Models;

use App\Observers\EmployeeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([EmployeeObserver::class])]
class Employee extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function unitStatuses()
    {
        return $this->hasMany(UnitStatus::class, 'operator_id');
    }
}
