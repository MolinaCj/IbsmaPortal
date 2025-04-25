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
        Schema::table('students', function (Blueprint $table) {
            Schema::table('students', function (Blueprint $table) {
                // Drop the foreign key constraint before modifying the column
                // $table->dropForeign(['department_id']);
                // $table->string('department_id')->nullable()->change();
                // // Re-add the foreign key constraint
                // $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
                $table->integer('year_level')->nullable()->change();
                $table->string('semester')->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            Schema::table('students', function (Blueprint $table) {
                // $table->dropForeign(['department_id']);
                // $table->string('dapartment_id')->nullable(false)->change();
                // $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
                $table->integer('year_level')->nullable(false)->change();
                $table->string('semester')->nullable(false)->change();
            });
        });
    }
};
