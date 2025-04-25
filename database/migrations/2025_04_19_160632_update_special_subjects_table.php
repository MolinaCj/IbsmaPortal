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
            // Change the 'day' column to an enum with the valid days of the week
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
                  ->nullable()
                  ->change(); // Ensure the 'day' column uses the enum data type
    
            // Ensure 'time' column is of the correct type (time type for the class schedule)
            $table->time('time')->nullable()->change(); // Ensure 'time' column is of type time
        });
    }
    
    public function down()
    {
        Schema::table('special_subjects', function (Blueprint $table) {
            // Revert the 'day' column to a string (if needed)
            $table->string('day')->nullable()->change();
    
            // Revert 'time' column back to its previous state (if necessary)
            $table->time('time')->nullable()->change(); // In case 'time' was already a time field
        });
    }
};
