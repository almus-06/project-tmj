<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Scope filter rentang tanggal (created_at) yang dipakai bersama oleh
 * tampilan tabel, statistik, dan export agar logikanya selalu sama.
 */
trait FilterRentangTanggal
{
    /**
     * Batasi created_at pada rentang tanggal (format Y-m-d). Salah satu
     * atau keduanya boleh kosong.
     */
    public function scopeRentangTanggal(Builder $query, ?string $mulai, ?string $sampai): Builder
    {
        $mulai = $mulai ?: null;
        $sampai = $sampai ?: null;

        if ($mulai && $sampai) {
            return $query->whereBetween('created_at', ["{$mulai} 00:00:00", "{$sampai} 23:59:59"]);
        }
        if ($mulai) {
            return $query->whereDate('created_at', '>=', $mulai);
        }
        if ($sampai) {
            return $query->whereDate('created_at', '<=', $sampai);
        }

        return $query;
    }

    /** Batasi berdasarkan nama proyek (kosong = semua proyek). */
    public function scopeProyek(Builder $query, ?string $namaProyek): Builder
    {
        if (! $namaProyek) {
            return $query;
        }

        return $query->whereHas('project', fn (Builder $q) => $q->where('name', $namaProyek));
    }
}
