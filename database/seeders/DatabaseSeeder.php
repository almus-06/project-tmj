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

        \App\Models\Employee::create(['name' => 'John Doe', 'position' => 'Operator']);
        \App\Models\Employee::create(['name' => 'Jane Smith', 'position' => 'Mechanic']);
        
        \App\Models\Unit::create(['unit_number' => 'DT-001', 'type' => 'Dump Truck', 'qr_code_string' => 'tmj-dt001']);
        \App\Models\Unit::create(['unit_number' => 'EX-022', 'type' => 'Excavator', 'qr_code_string' => 'tmj-ex022']);
    }
}
