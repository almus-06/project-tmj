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
        User::factory()->create([
            'name' => 'Developer',
            'email' => 'dev@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Administator',
            'email' => 'admin@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Human Resource TMJ',
            'email' => 'hr@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'hrd'
        ]);

        User::factory()->create([
            'name' => 'Workshop',
            'email' => 'workshop@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'workshop'
        ]);

        // Import Master Data from SQL
        $this->call(SqlDataSeeder::class);
    }
}
