<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithStyles
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected string $schoolYear;

    public function __construct(string $schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    public function collection()
    {
        return Student::query()
            ->with('department')
            ->where('school_year', $this->schoolYear)
            ->where(function ($query) {
                $query->where('graduated', 0)
                    ->where('enrolled', 1);
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
            $student->middle_name ?? '', // Handle null middle names
            $student->department->name ?? 'N/A',
            $student->year_level,
            $student->semester,
            $this->getStatus($student),
            $student->email,
            $student->cell_no ?? 'N/A' // Handle null phone numbers
        ];
    }

    public function title(): string
    {
        return 'Students';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2']
                ]
            ],
            // Auto-size columns
            'A:J' => ['autosize' => true],
        ];
    }

    protected function getStatus(Student $student): string
    {
        if ($student->graduated) return 'Graduated';
        return $student->enrolled ? 'Enrolled' : 'Inactive';
    }
}