<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class EmployeeCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_karyawan_baru_langsung_muncul_di_form_absensi(): void
    {
        Employee::create(['name' => 'Karyawan Lama', 'position' => 'Operator']);

        // Panggilan pertama mengisi cache 'employees_list'
        $this->get('/attendance')->assertOk()->assertSee('Karyawan Lama')->assertDontSee('Karyawan Baru');
        $this->assertTrue(Cache::has('employees_list'));

        Employee::create(['name' => 'Karyawan Baru', 'position' => 'Operator']);

        $this->assertFalse(Cache::has('employees_list'), 'Cache harus dibersihkan setelah karyawan dibuat');
        $this->get('/attendance')->assertOk()->assertSee('Karyawan Baru');
    }

    public function test_cache_dibersihkan_saat_karyawan_diubah_dan_dihapus(): void
    {
        $karyawan = Employee::create(['name' => 'Nama Awal', 'position' => 'Operator']);

        $this->get('/attendance')->assertOk();
        $this->assertTrue(Cache::has('employees_list'));

        $karyawan->update(['name' => 'Nama Diubah']);
        $this->assertFalse(Cache::has('employees_list'));

        $this->get('/attendance')->assertOk()->assertSee('Nama Diubah');
        $this->assertTrue(Cache::has('employees_list'));

        $karyawan->delete();
        $this->assertFalse(Cache::has('employees_list'));
        $this->get('/attendance')->assertOk()->assertDontSee('Nama Diubah');
    }
}
