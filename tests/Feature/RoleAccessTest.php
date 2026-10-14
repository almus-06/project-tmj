<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_membuka_halaman_karyawan(): void
    {
        $this->actingAs($this->buatUser('admin'))
            ->get('/operations-dashboard/employees')
            ->assertOk();
    }

    public function test_hrd_bisa_membuka_halaman_karyawan(): void
    {
        $this->actingAs($this->buatUser('hrd'))
            ->get('/operations-dashboard/employees')
            ->assertOk();
    }

    public function test_supervisor_tidak_bisa_membuka_halaman_karyawan(): void
    {
        $this->actingAs($this->buatUser('supervisor'))
            ->get('/operations-dashboard/employees')
            ->assertRedirect(route('operations.dashboard'));
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/operations-dashboard/employees')->assertRedirect(route('login'));
    }

    private function buatUser(string $role): User
    {
        $user = new User([
            'name' => 'Uji ' . $role,
            'username' => 'uji_' . $role,
            'password' => Hash::make('password123'),
        ]);
        $user->forceFill(['role' => $role])->save();

        return $user;
    }
}
