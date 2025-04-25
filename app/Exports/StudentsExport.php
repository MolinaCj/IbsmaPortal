<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Graduate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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

class StudentsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $schoolYear;

    public function __construct($schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    public function collection()
    {
        return Student::with('department')
            ->where('school_year', $this->schoolYear)
            ->where(function ($query) {
                $query->where('graduated', '!=', 1)
                    ->orWhere('enrolled', '!=', 0);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Last Name',
            'First Name',
            'Middle Name',
            'Department',
            'Year Level',
            'Semester',
            'Status',
            'Email',
            'Contact Number'
        ];
    }

    public function map($student): array
    {
        return [
            $student->student_id,
            $student->last_name,
            $student->first_name,
            $student->middle_name,
            $student->department->name ?? 'N/A',
            $student->year_level,
            $student->semester,
            $student->graduated ? 'Graduated' : ($student->enrolled ? 'Enrolled' : 'Inactive'),
            $student->email,
            $student->cell_no
        ];
    }

    public function title(): string
    {
        return 'Students';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:J' => ['autosize' => true],
        ];
    }
}

class GradesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $schoolYear;

    public function __construct($schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    public function collection()
    {
        return Grade::with(['student', 'subject', 'teacher', 'department'])
            ->where('school_year', $this->schoolYear)
            ->orderBy('year_level')
            ->orderBy('semester')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Department',
            'Year Level',
            'Semester',
            'Subject Code',
            'Subject Name',
            'Grade',
            'Teacher',
            'Status'
        ];
    }

    public function map($grade): array
    {
        return [
            $grade->student->student_id,
            $grade->student->last_name . ', ' . $grade->student->first_name,
            $grade->department->name ?? 'N/A',
            $grade->year_level,
            $grade->semester,
            $grade->subject->code ?? 'N/A',
            $grade->subject->name ?? 'N/A',
            $grade->grade ?? ($grade->incomplete ? 'INC' : 'N/A'),
            $grade->teacher->name ?? 'N/A',
            $grade->special ? 'Special Class' : 'Regular'
        ];
    }

    public function title(): string
    {
        return 'Grades';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:J' => ['autosize' => true],
        ];
    }
}

class GraduatesSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $schoolYear;

    public function __construct($schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    public function collection()
    {
        return Graduate::with(['student', 'department'])
            ->where('graduation_year', $this->schoolYear)
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Name',
            'Department',
            'Graduation Year'
        ];
    }

    public function map($graduate): array
    {
        return [
            $graduate->student_id,
            $graduate->name,
            $graduate->department->name ?? 'N/A',
            $graduate->graduation_year
        ];
    }

    public function title(): string
    {
        return 'Graduates';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:D' => ['autosize' => true],
        ];
    }
}