<?php

namespace App\Observers;

use App\Models\Employee;
use Illuminate\Support\Facades\Cache;

/**
 * Membersihkan cache daftar karyawan setiap kali data karyawan berubah,
 * agar form absensi dan fleet langsung menampilkan data terbaru.
 */
class EmployeeObserver
{
    public function created(Employee $employee): void
    {
        $this->bersihkanCache();
    }

    public function updated(Employee $employee): void
    {
        $this->bersihkanCache();
    }

    public function deleted(Employee $employee): void
    {
        $this->bersihkanCache();
    }

    private function bersihkanCache(): void
    {
        Cache::forget('employees_list');
    }
}
