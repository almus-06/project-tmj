<?php

namespace Tests\Feature\Database;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setSeedPassword('passwordseed123');
    }

    protected function tearDown(): void
    {
        $this->setSeedPassword('');
        parent::tearDown();
    }

    public function test_seeder_membuat_akun_dengan_role_dari_daftar_dan_password_dari_env(): void
    {
        $this->seed(UserSeeder::class);

        $admin = User::where('username', 'almuss')->first();
        $this->assertNotNull($admin);
        $this->assertSame('admin', $admin->role);
        $this->assertSame('hrd', User::where('username', 'IndaUlandari')->value('role'));
        $this->assertSame('workshop', User::where('username', 'TamKholik')->value('role'));
        $this->assertTrue(Hash::check('passwordseed123', $admin->password));
    }

    public function test_seeder_idempoten_tidak_menduplikasi_akun(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(UserSeeder::class);

        $this->assertSame(1, User::where('username', 'almuss')->count());
    }

    public function test_seeder_menolak_jika_password_env_kosong(): void
    {
        $this->setSeedPassword('');

        $this->expectException(RuntimeException::class);
        $this->seed(UserSeeder::class);
    }

    public function test_seeder_menolak_berjalan_di_production(): void
    {
        // Seeder dipanggil langsung (bukan lewat `db:seed`) agar yang diuji
        // adalah penjaga di dalam seeder, bukan konfirmasi bawaan artisan.
        $this->app['env'] = 'production';

        try {
            foreach ([DatabaseSeeder::class, UserSeeder::class] as $seeder) {
                try {
                    $this->app->make($seeder)->run();
                    $this->fail("{$seeder} seharusnya menolak berjalan di production");
                } catch (RuntimeException $e) {
                    $this->assertStringContainsString('production', $e->getMessage());
                }
            }
        } finally {
            $this->app['env'] = 'testing';
        }

        $this->assertSame(0, User::count());
    }

    private function setSeedPassword(string $value): void
    {
        putenv("SEED_DEFAULT_PASSWORD={$value}");
        $_ENV['SEED_DEFAULT_PASSWORD'] = $value;
        $_SERVER['SEED_DEFAULT_PASSWORD'] = $value;
    }
}
