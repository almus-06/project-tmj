<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

#[Signature('user:reset-password
    {username : Username akun yang password-nya akan diganti}
    {--password= : Password baru (minimal 8 karakter); jika kosong akan ditanya secara tersembunyi}')]
#[Description('Mengganti password sebuah akun dashboard')]
class UserResetPassword extends Command
{
    public function handle(): int
    {
        $username = $this->argument('username');
        $user = User::where('username', $username)->first();

        if (! $user) {
            $this->error("Akun dengan username \"{$username}\" tidak ditemukan.");
            return self::FAILURE;
        }

        $password = $this->option('password');
        if ($password === null || $password === '') {
            $password = $this->secret('Password baru (minimal 8 karakter)');
            $confirm = $this->secret('Ulangi password baru');
            if ($password !== $confirm) {
                $this->error('Password dan konfirmasi tidak sama.');
                return self::FAILURE;
            }
        }
        if (strlen((string) $password) < 8) {
            $this->error('Password minimal 8 karakter.');
            return self::FAILURE;
        }

        $user->forceFill(['password' => Hash::make($password)])->save();

        $this->info("Password akun \"{$username}\" berhasil diganti.");

        return self::SUCCESS;
    }
}
