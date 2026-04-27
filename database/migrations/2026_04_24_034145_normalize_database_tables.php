<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks for truncation
        Schema::disableForeignKeyConstraints();
        DB::table('attendances')->truncate();
        DB::table('unit_statuses')->truncate();
        Schema::enableForeignKeyConstraints();

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('project');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            // Add Indexes
            $table->index('created_at');
            $table->index(['employee_id', 'created_at']);
            $table->index('fit_status');
        });

        Schema::table('unit_statuses', function (Blueprint $table) {
            $table->dropColumn(['project', 'operator_name']);
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('employees')->onDelete('cascade');
            
            // Add Indexes
            $table->index('created_at');
            $table->index(['unit_id', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('unit_statuses', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['operator_id']);
            $table->dropColumn(['project_id', 'operator_id']);
            $table->string('project');
            $table->string('operator_name');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
            $table->string('project');
        });
    }
};
