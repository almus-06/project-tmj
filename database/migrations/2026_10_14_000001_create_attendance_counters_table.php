<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel counter nomor urut absensi per hari. Baris per tanggal dikunci
     * (lockForUpdate) saat membuat kode absensi sehingga submit bersamaan
     * tidak menghasilkan nomor urut yang sama.
     */
    public function up(): void
    {
        Schema::create('attendance_counters', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->unsignedInteger('last_seq')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_counters');
    }
};
