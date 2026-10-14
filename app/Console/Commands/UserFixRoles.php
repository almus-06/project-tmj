<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\DefaultAccounts;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('user:fix-roles
    {--dry-run : Hanya tampilkan perubahan, jangan simpan apa pun}')]
#[Description('Memperbaiki kolom role akun bawaan sesuai daftar di DefaultAccounts (idempoten)')]
class UserFixRoles extends Command
{
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $users = User::whereIn('username', array_keys(DefaultAccounts::ACCOUNTS))
            ->get()
            ->keyBy('username');

        $rows = [];
        $changes = [];
        foreach (DefaultAccounts::ACCOUNTS as $username => $account) {
            $user = $users->get($username);
            if (! $user) {
                $rows[] = [$username, '(tidak ada)', $account['role'], 'dilewati'];
                continue;
            }

            $current = $user->role ?? '(kosong)';
            $target = $account['role'];
            if ($user->role === $target) {
                $rows[] = [$username, $current, $target, 'sudah benar'];
                continue;
            }

            $rows[] = [$username, $current, $target, $dryRun ? 'akan diubah' : 'diubah'];
            $changes[] = [$user, $target];
        }

        $this->table(['username', 'role sekarang', 'role baru', 'keterangan'], $rows);

        if ($dryRun) {
            $this->info(count($changes) . ' akun akan diubah. Tidak ada yang disimpan (--dry-run).');
            return self::SUCCESS;
        }

        foreach ($changes as [$user, $target]) {
            // Hanya kolom role yang diubah; kolom lain tidak disentuh.
            User::whereKey($user->id)->update(['role' => $target]);
        }

        $this->info(count($changes) . ' akun diperbarui.');

        return self::SUCCESS;
    }
}
