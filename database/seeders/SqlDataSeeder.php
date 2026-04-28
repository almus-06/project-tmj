<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SqlDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Importing data from SQL files...');

        // 1. Import Karyawan (Employees)
        $karyawanSql = base_path('karyawan-tmj.sql');
        if (File::exists($karyawanSql)) {
            $this->command->info('Seeding Employees...');
            DB::unprepared(File::get($karyawanSql));
        } else {
            $this->command->error('File karyawan-tmj.sql not found at ' . $karyawanSql);
        }

        // 2. Import Unit Kendaraan (Units)
        $unitSql = base_path('unit-kendaraan-tmj.sql');
        if (File::exists($unitSql)) {
            $this->command->info('Seeding Units...');
            DB::unprepared(File::get($unitSql));
        } else {
            $this->command->error('File unit-kendaraan-tmj.sql not found at ' . $unitSql);
        }

        $this->command->info('SQL Data Import Completed!');
    }
}
