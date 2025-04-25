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
            $table->string('address')->nullable()->change();
            $table->string('suffix')->nullable()->change();
            $table->string('section')->nullable()->change();
            $table->integer('tertiary_grad_year')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('address')->nullable(false)->change();
            $table->string('suffix')->nullable(false)->change();
            $table->string('section')->nullable(false)->change();
            $table->integer('tertiary_grad_year')->nullable(false)->change();
        });
    }
};
