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
                ['no_kendaraan' => 'DT-001'],
                [
                    'jenis_alat'    => 'Dump Truck',
                    'merek'         => 'HINO',
                    'no_plat'       => 'B 1234 ABC',
                    'serial_number' => 'SN001',
                    'no_machine'    => 'MC001',
                    'ct'            => 'CT001',
                    'tahun'         => '2022',
                    'status'        => 'Ready',
                    'qr_code_string' => 'DT-001',
                ]
            );

            Unit::updateOrCreate(
                ['no_kendaraan' => 'EX-022'],
                [
                    'jenis_alat'    => 'Excavator',
                    'merek'         => 'KOMATSU',
                    'no_plat'       => '-',
                    'serial_number' => 'SN022',
                    'no_machine'    => 'MC022',
                    'ct'            => 'CT022',
                    'tahun'         => '2021',
                    'status'        => 'Ready',
                    'qr_code_string' => 'EX-022',
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
            if (count($data) < 6) continue;

            $noKendaraan = trim($data[5] ?? '');
            
            // Skip if no kendaraan is empty
            if (empty($noKendaraan)) continue;

            Unit::updateOrCreate(
                ['no_kendaraan' => $noKendaraan],
                [
                    'jenis_alat'    => trim($data[3] ?? ''),
                    'merek'         => trim($data[4] ?? ''),
                    'no_plat'       => trim($data[6] ?? ''),
                    'serial_number' => trim($data[7] ?? ''),
                    'no_machine'    => trim($data[8] ?? ''),
                    'ct'            => trim($data[9] ?? ''),
                    'tahun'         => trim($data[10] ?? ''),
                    'status'        => trim($data[11] ?? ''),
                    'qr_code_string' => $noKendaraan,
                ]
            );
        }

        fclose($file);
    }
}
