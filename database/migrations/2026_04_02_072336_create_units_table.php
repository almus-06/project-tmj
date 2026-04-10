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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_alat')->nullable();
            $table->string('merek')->nullable();
            $table->string('no_kendaraan')->unique();
            $table->string('no_plat')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('no_machine')->nullable();
            $table->string('ct')->nullable();
            $table->string('tahun')->nullable();
            $table->string('status')->nullable();
            $table->string('qr_code_string')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
