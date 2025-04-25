<?php

namespace App\Exports;

use App\Models\Graduate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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