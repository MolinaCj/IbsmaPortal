<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GradesSheet implements FromArray, WithHeadings, WithTitle, WithStyles, WithEvents

{
    protected $schoolYear;
    protected $departmentHeaderRows = [];


    public function __construct($schoolYear)
    {
        $this->schoolYear = $schoolYear;
    }

    public function array(): array
    {
        // 1) Fetch & group
        $groups = Grade::with(['student', 'subject', 'teacher', 'department'])
            ->where('school_year', $this->schoolYear)
            ->whereHas('student', fn($q) =>
                $q->where(fn($q2) => $q2->where('graduated', 1)
                                         ->orWhere('enrolled', 1))
            )
            ->get()
            ->groupBy('department_id');

        $data = [];

        foreach ($groups as $deptId => $grades) {
            $deptName = $grades->first()->department->name;

            // 2) Two blank rows for our 2‑row header
            $data[] = array_fill(0, 10, null);
            $data[] = array_fill(0, 10, null);

            // 3) Track where that header starts
            $this->departmentHeaderRows[] = [
                'row'  => count($data) - 1,   // 1‑based Excel row of the *first* blank
                'name' => $deptName,
            ];

            // 4) Push all grade rows
            foreach ($grades as $grade) {
                $data[] = [
                    $grade->student->student_id,
                    $grade->student->last_name . ', ' . $grade->student->first_name,
                    $deptName,
                    $grade->year_level,
                    $grade->semester,
                    $grade->subject->code   ?? 'N/A',
                    $grade->subject->name   ?? 'N/A',
                    $grade->grade           ?? ($grade->incomplete ? 'INC' : 'N/A'),
                    $grade->teacher->name   ?? 'N/A',
                    $grade->special ? 'Special Class' : 'Regular',
                ];
            }

            // 5) One more blank to separate groups
            $data[] = array_fill(0, 10, null);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Student ID', 'Student Name', 'Department',
            'Year Level', 'Semester', 'Subject Code',
            'Subject Name', 'Grade', 'Teacher', 'Status',
        ];
    }
    
    public function title(): string
    {
        return 'Grades';
    }

    public function styles(Worksheet $sheet)
    {
        // Bold the header row
        return [1 => ['font' => ['bold' => true]]];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                foreach ($this->departmentHeaderRows as $header) {
                    $start = $header['row'];
                    $end   = $start + 1;
                    $range = "A{$start}:J{$end}";

                    // 1) Merge the two rows
                    $sheet->mergeCells($range);

                    // 2) Write the dept title into the top cell
                    $title = str_repeat('-', 5) . ' ' . $header['name'] . ' ' . str_repeat('-', 5);
                    $sheet->setCellValue("A{$start}", $title);

                    // 3) Style it
                    $style = $sheet->getStyle($range);
                    $style->getFont()
                          ->setBold(true)
                          ->setSize(14);
                    $style->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                          ->setVertical  (Alignment::VERTICAL_CENTER);
                    $style->getFill()
                          ->setFillType(Fill::FILL_SOLID)
                          ->getStartColor()
                          ->setRGB('D9D9D9');
                }

                // (Optional) auto‑size columns A–J:
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    /**
     * Add department headers and blank rows.
     */
    // public function afterSheet(Worksheet $sheet)
    // {
    //     $row = 2;  // Start after headings
    //     $previousDepartment = null;

    //     // Iterate over rows and add department headers and blank rows
    //     foreach ($sheet->getRowIterator() as $rowIterator) {
    //         $currentRow = $rowIterator->getRowIndex();  // Get the current row index

    //         // Get department name for the current row
    //         $department = $sheet->getCell('C' . $currentRow)->getValue(); // Department is in column C

    //         // If the department is different from the previous one, insert a blank row and department header
    //         if ($department !== $previousDepartment) {
    //             if ($previousDepartment !== null) {
    //                 // Add a blank row before the next department group
    //                 $sheet->insertNewRowBefore($currentRow, 1);  // Insert a blank row
    //                 $currentRow++;  // Increment row after inserting a blank row
    //             }

    //             // Add the department header row
    //             $sheet->setCellValue('A' . $currentRow, '-------------- ' . $department . ' --------------');
    //             $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
    //             $sheet->getStyle('A' . $currentRow)->getFont()->setSize(14);
    //             $sheet->mergeCells('A' . $currentRow . ':J' . $currentRow);  // Merge cells across all columns
    //         }

    //         // Update previous department for comparison
    //         $previousDepartment = $department;
    //     }
    // }
}
