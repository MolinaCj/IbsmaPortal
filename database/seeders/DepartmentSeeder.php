<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('departments')->truncate();

        // Enable foreign key checks back
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert department data
        $departments = [
            ['name' => 'BSIT'],
            ['name' => 'BSA'],
            ['name' => 'BSBA'],
            ['name' => 'Criminology'],
            // ['name' => 'Midwifery'],
            ['name' => 'Others'],
        ];

        Department::insert($departments);
    }
}
