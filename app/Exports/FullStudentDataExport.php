<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FullStudentDataExport implements WithMultipleSheets
{
    protected $schoolYear;

    public function __construct($schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    public function sheets(): array
    {
        return [
            'Students' => new StudentsSheet($this->schoolYear),
            'Grades' => new GradesSheet($this->schoolYear),
            'Graduates' => new GraduatesSheet($this->schoolYear),
        ];
    }
}