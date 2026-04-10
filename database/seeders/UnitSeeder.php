<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = base_path('DATABASE UNIT UPDATE NEW - Salin.csv');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: $filePath");

            // Fallback to sample data for development
            Unit::updateOrCreate(
                ['no_kendaraan' => 'Test-001'],
                [
                    'jenis_alat' => 'Test',
                    'merek' => 'Test',
                    'no_plat' => 'Test',
                    'serial_number' => 'Test',
                    'no_machine' => 'Test',
                    'ct' => 'ct.0101.001',
                    'tahun' => 'Test',
                    'status' => 'Ready',
                    'qr_code_string' => 'Test-001',
                ]
            );

            return;
        }

        $file = fopen($filePath, 'r');

        // Skip first 8 lines (headers and empty space)
        for ($i = 0; $i < 8; $i++) {
            fgets($file);
        }

        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            // Check if there is enough data (at least up to NO KENDARAAN at index 5)
            if (count($data) < 6)
                continue;

            $noKendaraan = trim($data[5] ?? '');

            // Skip if no kendaraan is empty
            if (empty($noKendaraan))
                continue;

            Unit::updateOrCreate(
                ['no_kendaraan' => $noKendaraan],
                [
                    'jenis_alat' => trim($data[3] ?? ''),
                    'merek' => trim($data[4] ?? ''),
                    'no_plat' => trim($data[6] ?? ''),
                    'serial_number' => trim($data[7] ?? ''),
                    'no_machine' => trim($data[8] ?? ''),
                    'ct' => trim($data[9] ?? ''),
                    'tahun' => trim($data[10] ?? ''),
                    'status' => trim($data[11] ?? ''),
                    'qr_code_string' => $noKendaraan,
                ]
            );
        }

        fclose($file);
    }
}
