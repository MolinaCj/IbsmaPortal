<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('special_subjects', function (Blueprint $table) {
            $table->text('schedule')->nullable();

            // Optional: drop old columns if you're done with them
            $table->dropColumn(['day', 'start_time', 'end_time', 'time']);
        });
    }

    public function down()
    {
        Schema::table('special_subjects', function (Blueprint $table) {
            $table->dropColumn('schedule');

            // Restore old columns (if rolling back)
            $table->string('day')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('time')->nullable();
        });
    }
};
