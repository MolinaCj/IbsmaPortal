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
    Schema::table('grades', function (Blueprint $table) {
        $table->boolean('incomplete')->nullable()->default(null); // Add incomplete column
    });
}

public function down()
{
    Schema::table('grades', function (Blueprint $table) {
        $table->dropColumn('incomplete'); // Drop incomplete column if rollback
    });
}

};
