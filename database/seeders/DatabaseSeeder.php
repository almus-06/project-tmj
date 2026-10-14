<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('DatabaseSeeder tidak boleh dijalankan di production.');
        }

        // Akun bawaan (password dari SEED_DEFAULT_PASSWORD di .env)
        $this->call(UserSeeder::class);

        // Import Master Data from SQL
        $this->call(SqlDataSeeder::class);
    }
}
