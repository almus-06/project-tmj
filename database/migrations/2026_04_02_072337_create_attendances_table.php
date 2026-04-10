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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('attendance_code')->unique();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('presence_status');
            $table->string('blood_pressure');
            $table->integer('spo2');
            $table->decimal('temperature', 5, 2);
            $table->boolean('tak');
            $table->string('project');
            $table->string('fit_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
