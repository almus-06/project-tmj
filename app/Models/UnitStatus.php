<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_id',
        'operator_id',
        'project_id',
        'status',
        'location',
        'damage_type',
        'hm',
        'km',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function operator()
    {
        return $this->belongsTo(Employee::class, 'operator_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
