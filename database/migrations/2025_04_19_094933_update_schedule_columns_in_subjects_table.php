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
            // If these columns already exist, modify them:
            $table->string('day')->nullable()->change(); // e.g., 'Monday', 'Tue-Thu'
            $table->string('time')->nullable()->change(); // or use ->time('time')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Revert back to original types (guessing previous type here)
            $table->dropColumn('day');
            $table->dropColumn('time');
        });
    }
};
