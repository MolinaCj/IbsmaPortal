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
        Schema::table('subjects', function (Blueprint $table) {
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
                ->nullable()
                ->change();  // Modify the existing 'day' column
            $table->time('start_time')->nullable();  // Start time for the class
            $table->time('end_time')->nullable();    // End time for the class
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Revert the 'day' column back to string (or its previous type)
            $table->string('day')->nullable()->change();
            
            // Drop the new columns
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
