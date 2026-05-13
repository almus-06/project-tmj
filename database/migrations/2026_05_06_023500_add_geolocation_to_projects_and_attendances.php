<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add geolocation columns to projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('radius_meters')->default(100)->after('longitude');
        });

        // Set the 3 default projects to the test coordinate and 100m radius
        DB::table('projects')->update([
            'latitude' => -2.62330530,
            'longitude' => 121.36963140,
            'radius_meters' => 20,
            'updated_at' => now(),
        ]);

        // Add geolocation and verification columns to attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('shift');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->double('distance_from_project')->nullable()->after('longitude');
            $table->boolean('is_inside_radius')->nullable()->after('distance_from_project');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'distance_from_project', 'is_inside_radius']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meters']);
        });
    }
};
