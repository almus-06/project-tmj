<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Developer',
            'username' => 'almuss',
            'email' => null,
            'password' => bcrypt('FksKlh2327'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Inda Ulandari',
            'username' => 'IndaUlandari',
            'email' => null,
            'password' => bcrypt('TMJSafetyNumber01'),
            'role' => 'hrd'
        ]);

        User::create([
            'name' => 'Syahrul Ramadhan',
            'username' => 'SyahrulRamadhan',
            'email' => null,
            'password' => bcrypt('TMJSafetyNumber01'),
            'role' => 'workshop'
        ]);
        User::create([
            'name' => 'Ayatollah Khomeni',
            'username' => 'AyatollahKhomeni',
            'email' => null,
            'password' => bcrypt('TMJSafetyNumber01'),
            'role' => 'workshop'
        ]);

        User::create([
            'name' => 'Faldia Kurniawan',
            'username' => 'FaldiaKurniawan',
            'email' => null,
            'password' => bcrypt('TMJSafetyNumber01'),
            'role' => 'workshop'
        ]);

        User::create([
            'name' => 'Rudi Prayogo',
            'username' => 'RudiPrayogo',
            'email' => null,
            'password' => bcrypt('TMJSafetyNumber01'),
            'role' => 'workshop'
        ]);
        User::create([
            'name' => 'Tam Kholik',
            'username' => 'TamKholik',
            'email' => null,
            'password' => bcrypt('TMJSafetyNumber01'),
            'role' => 'workshop'
        ]);

        // Import Master Data from SQL
        $this->call(SqlDataSeeder::class);
    }
}
