<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Tipe absensi: 'clock_in' atau 'clock_out'
            if (!Schema::hasColumn('attendances', 'type')) {
                $table->string('type', 10)->default('clock_in')->after('attendance_code');
            }
            // UUID dari localStorage perangkat
            if (!Schema::hasColumn('attendances', 'device_fingerprint')) {
                $table->string('device_fingerprint')->nullable()->after('device_info');
            }
            // IP Address perangkat
            if (!Schema::hasColumn('attendances', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('device_fingerprint');
            }
        });

        // Tambahkan index secara aman
        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index(['created_at', 'device_fingerprint']);
            });
        } catch (\Exception $e) {
            // Abaikan jika index sudah ada
        }

        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index(['created_at', 'employee_id']);
            });
        } catch (\Exception $e) {
            // Abaikan jika index sudah ada
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            try {
                $table->dropIndex(['created_at', 'device_fingerprint']);
            } catch (\Exception $e) {}

            try {
                $table->dropIndex(['created_at', 'employee_id']);
            } catch (\Exception $e) {}

            $colsToDrop = [];
            if (Schema::hasColumn('attendances', 'type')) {
                $colsToDrop[] = 'type';
            }
            if (Schema::hasColumn('attendances', 'device_fingerprint')) {
                $colsToDrop[] = 'device_fingerprint';
            }
            if (Schema::hasColumn('attendances', 'ip_address')) {
                $colsToDrop[] = 'ip_address';
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
