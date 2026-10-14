<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\DefaultAccounts;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

#[Signature('user:create
    {--name= : Nama lengkap}
    {--username= : Username (unik)}
    {--email= : Email (opsional)}
    {--role= : Role: admin, hrd, workshop, supervisor}
    {--password= : Password (minimal 8 karakter); jika kosong akan ditanya secara tersembunyi}')]
#[Description('Membuat akun dashboard baru secara manual (interaktif)')]
class UserCreate extends Command
{
    public function handle(): int
    {
        $interaktif = $this->input->isInteractive();

        $name = trim((string) ($this->option('name') ?: ($interaktif ? $this->ask('Nama lengkap') : '')));
        if ($name === '') {
            $this->error('Nama wajib diisi.');
            return self::FAILURE;
        }

        $username = trim((string) ($this->option('username') ?: ($interaktif ? $this->ask('Username') : '')));
        if ($username === '') {
            $this->error('Username wajib diisi.');
            return self::FAILURE;
        }
        if (User::where('username', $username)->exists()) {
            $this->error("Username \"{$username}\" sudah dipakai.");
            return self::FAILURE;
        }

        $email = $this->option('email');
        if ($email === null && $interaktif) {
            $email = $this->ask('Email (opsional, tekan Enter untuk lewati)');
        }
        $email = trim((string) $email) ?: null;
        if ($email !== null) {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error('Format email tidak valid.');
                return self::FAILURE;
            }
            if (User::where('email', $email)->exists()) {
                $this->error("Email \"{$email}\" sudah dipakai.");
                return self::FAILURE;
            }
        }

        $role = $this->option('role') ?: ($interaktif ? $this->choice('Role', DefaultAccounts::ROLES, 3) : '');
        if (! in_array($role, DefaultAccounts::ROLES, true)) {
            $this->error('Role tidak valid. Pilihan: ' . implode(', ', DefaultAccounts::ROLES) . '.');
            return self::FAILURE;
        }

        $password = (string) $this->option('password');
        if ($password === '' && $interaktif) {
            $password = (string) $this->secret('Password (minimal 8 karakter)');
            $confirm = (string) $this->secret('Ulangi password');
            if ($password !== $confirm) {
                $this->error('Password dan konfirmasi tidak sama.');
                return self::FAILURE;
            }
        }
        if (strlen($password) < 8) {
            $this->error('Password minimal 8 karakter.');
            return self::FAILURE;
        }

        $user = new User([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        // Role diisi eksplisit lewat forceFill, bukan fillable, agar tidak bisa
        // diubah lewat mass-assignment dari request.
        $user->forceFill(['role' => $role])->save();

        $this->info("Akun \"{$username}\" ({$role}) berhasil dibuat.");

        return self::SUCCESS;
    }
}
