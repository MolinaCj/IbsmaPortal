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
        Schema::table('subjects', function (Blueprint $table) {
            // Drop the existing unique constraint on 'code'
            $table->dropUnique('subjects_code_unique'); 

            // Add a new unique constraint for (code, department_id)
            $table->unique(['code', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Rollback: Drop the new unique constraint and restore the old one
            $table->dropUnique(['code', 'department_id']);
            $table->unique('code');
        });
    }
};
