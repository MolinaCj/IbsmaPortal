<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Setting;
use App\Exports\FullStudentDataExport; // Add this import

class ExportController extends Controller
{
    public function exportFullStudentData(Request $request)
    {
        $year = $request->query('year', Setting::getSchoolYear());
        $filename = "student_data_export_{$year}_".now()->format('Ymd_His').'.xlsx';
        
        return Excel::download(new FullStudentDataExport($year), $filename);
    }
}