<?php

namespace App\Support;

/**
 * Daftar akun bawaan beserta role-nya.
 * Dipakai oleh DatabaseSeeder dan command user:fix-roles agar
 * sumber kebenarannya hanya satu.
 */
class DefaultAccounts
{
    /** Role yang dikenal aplikasi. */
    public const ROLES = ['admin', 'hrd', 'workshop', 'supervisor'];

    /** @var array<string, array{name: string, role: string}> username => data */
    public const ACCOUNTS = [
        'almuss'           => ['name' => 'Developer',         'role' => 'admin'],
        'IndaUlandari'     => ['name' => 'Inda Ulandari',     'role' => 'hrd'],
        'SyahrulRamadhan'  => ['name' => 'Syahrul Ramadhan',  'role' => 'workshop'],
        'AyatollahKhomeni' => ['name' => 'Ayatollah Khomeni', 'role' => 'workshop'],
        'FaldiaKurniawan'  => ['name' => 'Faldia Kurniawan',  'role' => 'workshop'],
        'RudiPrayogo'      => ['name' => 'Rudi Prayogo',      'role' => 'workshop'],
        'TamKholik'        => ['name' => 'Tam Kholik',        'role' => 'workshop'],
    ];
}
