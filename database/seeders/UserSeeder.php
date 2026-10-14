<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\DefaultAccounts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * Membuat akun bawaan dashboard.
 *
 * Password TIDAK ditulis di sini; dibaca dari variabel SEED_DEFAULT_PASSWORD
 * di .env. Seeder ini menolak berjalan di production.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('UserSeeder tidak boleh dijalankan di production. Gunakan `php artisan user:create`.');
        }

        $password = (string) env('SEED_DEFAULT_PASSWORD', '');
        if (strlen($password) < 8) {
            throw new RuntimeException('SEED_DEFAULT_PASSWORD belum diisi di .env (minimal 8 karakter).');
        }

        foreach (DefaultAccounts::ACCOUNTS as $username => $account) {
            if (User::where('username', $username)->exists()) {
                continue; // idempoten: akun yang sudah ada tidak disentuh
            }

            $user = new User([
                'name' => $account['name'],
                'username' => $username,
                'email' => null,
                'password' => Hash::make($password),
            ]);
            // Role bukan bagian dari fillable, jadi diisi lewat forceFill.
            $user->forceFill(['role' => $account['role']])->save();
        }
    }
}
