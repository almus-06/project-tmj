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
            $table->double('accuracy')->nullable()->after('longitude')->comment('Akurasi GPS dalam meter');
            $table->double('altitude')->nullable()->after('accuracy');
            $table->double('heading')->nullable()->after('altitude');
            $table->double('speed')->nullable()->after('heading')->comment('Kecepatan m/s');
            $table->string('device_info')->nullable()->after('speed');
            $table->boolean('is_fake_gps_suspected')->default(false)->after('device_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'accuracy',
                'altitude',
                'heading',
                'speed',
                'device_info',
                'is_fake_gps_suspected'
            ]);
        });
    }
};
