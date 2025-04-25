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
        Schema::create('special_subjects', function (Blueprint $table) {
            $table->id();  // Auto-incrementing ID
            $table->string('name');  // Name of the special subject
            $table->text('description')->nullable();  // Course description for the special class
            $table->integer('units')->nullable();  // Number of units for the special class
            $table->string('day')->nullable();  // Day of the week (e.g., Monday, Tuesday)
            $table->string('time')->nullable();  // Time (e.g., 9:00 AM - 11:00 AM)
            $table->string('room')->nullable();  // Room number for the special class
            $table->unsignedBigInteger('subject_id');  // Foreign key for the regular subject
            $table->unsignedBigInteger('teacher_id');  // Foreign key for the teacher assigned to the special class
            $table->unsignedBigInteger('department_id');  // Foreign key for the department
            $table->integer('semester');  // Semester for the special class
            $table->integer('year');  // Year for the special class
            $table->timestamps();  // Created and updated timestamps

            // Foreign key constraints
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('special_subjects');
    }
};
