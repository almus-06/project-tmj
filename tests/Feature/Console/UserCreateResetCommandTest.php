<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCreateResetCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_create_membuat_akun_dengan_role_yang_benar(): void
    {
        $this->artisan('user:create', [
            '--name' => 'Budi',
            '--username' => 'budi',
            '--role' => 'hrd',
            '--password' => 'rahasia123',
            '--no-interaction' => true,
        ])->assertSuccessful();

        $user = User::where('username', 'budi')->first();
        $this->assertNotNull($user);
        $this->assertSame('hrd', $user->role);
        $this->assertNull($user->email);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_user_create_interaktif_menanyakan_data_dan_password_tersembunyi(): void
    {
        $this->artisan('user:create')
            ->expectsQuestion('Nama lengkap', 'Siti')
            ->expectsQuestion('Username', 'siti')
            ->expectsQuestion('Email (opsional, tekan Enter untuk lewati)', '')
            ->expectsChoice('Role', 'admin', ['admin', 'hrd', 'workshop', 'supervisor'])
            ->expectsQuestion('Password (minimal 8 karakter)', 'rahasia123')
            ->expectsQuestion('Ulangi password', 'rahasia123')
            ->assertSuccessful();

        $this->assertSame('admin', User::where('username', 'siti')->value('role'));
    }

    public function test_user_create_menolak_username_duplikat(): void
    {
        $this->buatUser('budi', 'supervisor');

        $this->artisan('user:create', [
            '--name' => 'Budi 2',
            '--username' => 'budi',
            '--role' => 'hrd',
            '--password' => 'rahasia123',
            '--no-interaction' => true,
        ])->assertFailed();

        $this->assertSame(1, User::where('username', 'budi')->count());
    }

    public function test_user_create_menolak_password_pendek_dan_role_tidak_valid(): void
    {
        $this->artisan('user:create', [
            '--name' => 'Budi', '--username' => 'budi', '--role' => 'hrd', '--password' => 'pendek', '--no-interaction' => true,
        ])->assertFailed();

        $this->artisan('user:create', [
            '--name' => 'Budi', '--username' => 'budi', '--role' => 'bos', '--password' => 'rahasia123', '--no-interaction' => true,
        ])->assertFailed();

        $this->assertSame(0, User::count());
    }

    public function test_user_reset_password_mengganti_password(): void
    {
        $this->buatUser('budi', 'supervisor');

        $this->artisan('user:reset-password', ['username' => 'budi', '--password' => 'barubaru123'])
            ->assertSuccessful();

        $this->assertTrue(Hash::check('barubaru123', User::where('username', 'budi')->first()->password));
    }

    public function test_user_reset_password_gagal_jika_akun_tidak_ada(): void
    {
        $this->artisan('user:reset-password', ['username' => 'tidakada', '--password' => 'barubaru123'])
            ->assertFailed();
    }

    private function buatUser(string $username, string $role): User
    {
        $user = new User([
            'name' => 'Developer Lama',
            'username' => $username,
            'password' => Hash::make('password-lama'),
        ]);
        $user->forceFill(['role' => $role])->save();

        return $user;
    }
}
