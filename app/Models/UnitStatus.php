<?php

namespace App\Models;

use App\Models\Concerns\FilterRentangTanggal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitStatus extends Model
{
    use HasFactory, FilterRentangTanggal;
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

    /**
     * Hanya status TERBARU setiap unit di dalam rentang tanggal.
     * Filter tanggal diterapkan dulu pada subquery MAX(id), baru diambil
     * status terakhirnya, sehingga unit tidak hilang hanya karena status
     * terakhirnya di luar rentang.
     */
    public function scopeTerbaruPerUnit(Builder $query, ?string $mulai = null, ?string $sampai = null): Builder
    {
        $idTerbaru = static::query()
            ->selectRaw('MAX(id)')
            ->rentangTanggal($mulai, $sampai)
            ->groupBy('unit_id');

        return $query->whereIn('id', $idTerbaru);
    }

    /** Batasi berdasarkan status (Ready/Standby/Down); kosong = semua. */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

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
