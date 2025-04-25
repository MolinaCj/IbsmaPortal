<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentsSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('app/Final_Student_Seeder_Complete.xlsx');
    
        if (!file_exists($filePath)) {
            $this->command->error("File not found: " . $filePath);
            return;
        }
    
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
    
        // Identify the header row dynamically
        $headers = array_shift($rows);
    
        // Get all valid department IDs
        $validDepartmentIds = DB::table('departments')->pluck('id')->toArray();
    
        foreach ($rows as $row) {
            // Ensure department_id is numeric and exists in the departments table
            $departmentId = is_numeric($row['AA']) ? (int) $row['AA'] : 1; // Default to 1
    
            if (!in_array($departmentId, $validDepartmentIds)) {
                $departmentId = 1; // If invalid, set to 1
            }
    
            // Convert date only if it's a valid date
            $birthdate = null;
            if (!empty($row['G']) && strtotime($row['G'])) {
                $birthdate = Carbon::parse($row['G'])->format('Y-m-d');
            }
    
            // Ensure elem_grad_year and hs_grad_year are valid numbers
            $elemGradYear = (!empty($row['V']) && is_numeric(trim($row['V']))) ? (int) trim($row['V']) : 2000; // Default to 2000
            $hsGradYear   = (!empty($row['W']) && is_numeric(trim($row['W']))) ? (int) trim($row['W']) : 2004; // Default to 2004
            $tertGradYear = (!empty($row['X']) && is_numeric(trim($row['X']))) ? (int) trim($row['X']) : 2004; // Default to 2004
    
            $studentId = trim($row['A'] ?? '');
            $yearLevel = is_numeric($row['Y']) ? (int) $row['Y'] : 1; // Default 1
            $semester = is_numeric($row['Z']) ? (int) $row['Z'] : 1; // Default 1
    
            // Set school year based on year level
            $schoolYear = 2025 - ($yearLevel - 1); // Adjust school year according to year level
    
            DB::table('students')->insert([
                'student_id'        => $studentId,
                'last_name'         => trim($row['B'] ?? ''),
                'first_name'        => trim($row['C'] ?? ''),
                'middle_name'       => trim($row['D'] ?? ''),
                'age'               => is_numeric($row['E']) ? (int) $row['E'] : null,
                'sex'               => trim($row['F'] ?? ''),
                'bdate'             => $birthdate,
                'bplace'            => trim($row['H'] ?? ''),
                'civil_status'      => trim($row['I'] ?? ''),
                'address'           => trim($row['J'] ?? ''),
                'email'             => trim($row['K'] ?? ''),
                'father_last_name'  => trim($row['L'] ?? ''),
                'father_first_name' => trim($row['M'] ?? ''),
                'father_middle_name'=> trim($row['N'] ?? ''),
                'mother_last_name'  => trim($row['O'] ?? ''),
                'mother_first_name' => trim($row['P'] ?? ''),
                'mother_middle_name'=> trim($row['Q'] ?? ''),
                'cell_no' => '0' . ltrim(trim($row['R'] ?? ''), '0'),
                'elem_school_name'  => trim($row['S'] ?? ''),
                'hs_school_name'    => trim($row['T'] ?? ''),
                'tertiary_school_name'=> trim($row['U'] ?? ''),
                'elem_grad_year'    => $elemGradYear,
                'hs_grad_year'      => $hsGradYear,
                'tertiary_grad_year'=> $tertGradYear,
                'department_id'     => $departmentId, // Set to 1 as default
                'year_level'        => $yearLevel,
                'semester'          => $semester,
                'school_year'       => $schoolYear,
                'enrolled'          => 1,
                'regular'           => 1, // Default to regular
                'password'          => bcrypt('12345678'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
    
            // Fetch subjects for all years up to the student's current year level
            $subjectsQuery = DB::table('subjects')->where('department_id', $departmentId)
                ->whereIn('year', range(1, $yearLevel)); // Get subjects up to the current year level
    
            $subjects = $subjectsQuery->get();
    
            // Insert subjects into the grades table with grade = 90
            if (!$subjects->isEmpty()) {
                $grades = [];
                foreach ($subjects as $subject) {
                    // Determine school year for each subject
                    if ($subject->year == 1) {
                        // For 1st year subjects, set school year to 2025 for 1st-year students
                        if ($yearLevel == 1) {
                            $subjectSchoolYear = 2025;
                        } else {
                            // For 2nd, 3rd, and 4th-year students, set school year based on when the subject was taken
                            $subjectSchoolYear = 2024 - ($yearLevel - 1);
                        }
                    } elseif ($subject->year == 2) {
                        // For 2nd year subjects, set school year to 2025 for current 2nd-year students
                        if ($yearLevel == 2) {
                            $subjectSchoolYear = 2025;
                        } else {
                            $subjectSchoolYear = 2024;
                        }
                    } elseif ($subject->year == 3) {
                        // For 3rd year subjects, set school year to 2025 for current 3rd-year students
                        if ($yearLevel == 3) {
                            $subjectSchoolYear = 2025;
                        } elseif ($yearLevel == 2) {
                            $subjectSchoolYear = 2024;
                        } else {
                            $subjectSchoolYear = 2023;
                        }
                    } elseif ($subject->year == 4) {
                        // For 4th year subjects, set school year to 2025 for current 4th-year students
                        if ($yearLevel == 4) {
                            $subjectSchoolYear = 2025;
                        } elseif ($yearLevel == 3) {
                            $subjectSchoolYear = 2024;
                        } elseif ($yearLevel == 2) {
                            $subjectSchoolYear = 2023;
                        } else {
                            $subjectSchoolYear = 2022;
                        }
                    }
                
                    $grades[] = [
                        'student_id'    => $studentId,
                        'department_id' => $departmentId,
                        'subject_id'    => $subject->id,
                        'semester'      => $subject->semester, // Use semester from subjects table
                        'year_level'    => $subject->year,     // Use year_level from subjects table
                        'school_year'   => $subjectSchoolYear, // School year based on subject year
                        'grade'         => 90, // Set all grades to 90
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
                
    
                // Insert grades in bulk for better performance
                DB::table('grades')->insert($grades);
            }
        }
    
        $this->command->info("Students seeded successfully!");
    }
    

}
