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
        Schema::create('students', function (Blueprint $table) {
            $table->string('student_id')->primary();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name');
            $table->integer('age');
            $table->string('sex');
            $table->date('bdate');
            $table->string('bplace');
            $table->string('civil_status');
            $table->string("address");
            $table->string('father_last_name');
            $table->string('father_first_name');
            $table->string('father_middle_name');
            $table->string('mother_last_name');
            $table->string('mother_first_name');
            $table->string('mother_middle_name');
            $table->integer('cell_no');
            $table->string('elem_school_name');
            $table->string('hs_school_name');
            $table->string('tertiary_school_name');
            $table->integer('elem_grad_year');
            $table->integer('hs_grad_year');
            $table->integer('tertiary_grad_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
