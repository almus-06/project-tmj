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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('location')->nullable();
            $table->timestamps();
        });

        // Insert default projects
        DB::table('projects')->insert([
            ['name' => 'Main Dev', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sorlim', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Big Fleet', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
