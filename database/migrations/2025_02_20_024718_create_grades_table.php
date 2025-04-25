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
    Schema::create('grades', function (Blueprint $table) {
        $table->id(); 
        
        // Ensure student_id is a string and properly indexed
        $table->string('student_id'); 
        $table->foreign('student_id')
              ->references('student_id') // Make sure students table has student_id as primary key
              ->on('students')
              ->onDelete('cascade');

        // Explicitly define tables
        $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
        $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');

        $table->integer('year_level');
        $table->integer('semester');
        $table->decimal('grade', 5, 2)->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
