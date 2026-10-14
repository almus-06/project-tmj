<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Aturan shift per project, menggantikan nama project yang di-hardcode
     * di AttendanceController. Jam 0–23; keduanya NULL berarti project
     * tidak punya shift ("Luar Shift").
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedTinyInteger('shift_pagi_mulai')->nullable()->after('radius_meters')
                ->comment('Jam mulai Shift Pagi (0-23); di luar rentang = Shift Malam');
            $table->unsignedTinyInteger('shift_pagi_selesai')->nullable()->after('shift_pagi_mulai')
                ->comment('Jam selesai Shift Pagi (0-23, eksklusif)');
        });

        // Isi nilai untuk project yang sudah ada agar perilaku saat ini tidak
        // berubah. Hanya baris yang masih NULL yang diisi (idempoten).
        DB::table('projects')
            ->whereIn('name', ['Main Dev', 'Sorlim'])
            ->whereNull('shift_pagi_mulai')
            ->update(['shift_pagi_mulai' => 6, 'shift_pagi_selesai' => 16]);

        DB::table('projects')
            ->where('name', 'Big Fleet')
            ->whereNull('shift_pagi_mulai')
            ->update(['shift_pagi_mulai' => 6, 'shift_pagi_selesai' => 18]);
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['shift_pagi_mulai', 'shift_pagi_selesai']);
        });
    }
};
