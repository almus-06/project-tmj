<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserFixRolesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_fix_roles_dry_run_tidak_mengubah_database(): void
    {
        $this->buatUser('almuss', 'supervisor');
        $this->buatUser('IndaUlandari', 'supervisor');

        $this->artisan('user:fix-roles', ['--dry-run' => true])->assertSuccessful();

        $this->assertSame('supervisor', User::where('username', 'almuss')->value('role'));
        $this->assertSame('supervisor', User::where('username', 'IndaUlandari')->value('role'));
    }

    public function test_user_fix_roles_hanya_mengubah_kolom_role(): void
    {
        $admin = $this->buatUser('almuss', 'supervisor');
        $lain = $this->buatUser('orang_lain', 'supervisor');
        $passwordSebelum = $admin->password;
        $updatedSebelum = $admin->updated_at;

        $this->artisan('user:fix-roles')->assertSuccessful();

        $admin->refresh();
        $this->assertSame('admin', $admin->role);
        $this->assertSame($passwordSebelum, $admin->password);
        $this->assertSame('Developer Lama', $admin->name, 'Nama tidak boleh ikut diubah');
        $this->assertEquals($updatedSebelum, $admin->updated_at, 'updated_at tidak boleh ikut berubah');

        // Akun di luar daftar tidak disentuh
        $this->assertSame('supervisor', $lain->fresh()->role);
    }

    public function test_user_fix_roles_idempoten(): void
    {
        $this->buatUser('almuss', 'supervisor');

        $this->artisan('user:fix-roles')->assertSuccessful();
        $this->artisan('user:fix-roles')->assertSuccessful();

        $this->assertSame('admin', User::where('username', 'almuss')->value('role'));
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
