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
        Schema::table('teachers', function (Blueprint $table) {
            $table->integer('login_attempts')->nullable()->change();
            $table->integer('lockout_count')->nullable()->change();
            $table->timestamp('lockout_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->integer('login_attempts')->nullable(false)->change();
        $table->integer('lockout_count')->nullable(false)->change();
        $table->timestamp('lockout_time')->nullable(false)->change();
        });
    }
};
