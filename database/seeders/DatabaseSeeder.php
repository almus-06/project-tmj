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
            'name' => 'Admin System',
            'email' => 'admin@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Supervisor TMJ',
            'email' => 'spv@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor'
        ]);

        User::factory()->create([
            'name' => 'HRD TMJ',
            'email' => 'hrd@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'hrd'
        ]);

        User::factory()->create([
            'name' => 'Kepala Workshop',
            'email' => 'workshop@tmj.com',
            'password' => bcrypt('password'),
            'role' => 'workshop'
        ]);

        \App\Models\Employee::create(['name' => 'John Doe', 'position' => 'Operator']);
        \App\Models\Employee::create(['name' => 'Jane Smith', 'position' => 'Mechanic']);
        
        $this->call([
            UnitSeeder::class,
        ]);
    }
}
