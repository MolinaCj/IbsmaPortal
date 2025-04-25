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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            // $table->string('code')->unique();  // Course code (e.g., CS101, MATH202)
            $table->string('name');  // Name of the subject
            $table->text('description')->nullable();  // Course description
            $table->integer('units')->nullable();  // Number of units for the course
            $table->string('day')->nullable();  // Day of the week (e.g., Monday, Tuesday)
            $table->string('time')->nullable();  // Time (e.g., 9:00 AM - 11:00 AM)
            $table->string('room')->nullable();  // Room number
            $table->string('instructor')->nullable();  // Instructor's name
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
