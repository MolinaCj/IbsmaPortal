<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['current_semester', 'current_school_year','grading_locked'];

    // Get the latest semester
    public static function getSemester()
    {
        return self::latest('id')->value('current_semester') ?? 1;
    }

    // Get the latest school year
    public static function getSchoolYear()
    {
        return self::latest('id')->value('current_school_year') ?? 2020;
    }

    // Get all distinct school years for selection in export
    public static function getAvailableSchoolYears()
    {
        return self::orderBy('current_school_year', 'desc')->pluck('current_school_year')->unique();
    }

    // Increment the school year (called when moving to a new academic year)
    public static function incrementSchoolYear()
    {
        $setting = self::latest('id')->first();
        if ($setting) {
            $setting->update(['current_school_year' => (int) $setting->current_school_year + 1]);
        } else {
            self::create([
                'current_semester' => 1,
                'current_school_year' => date('Y') + 1,
            ]);
        }
    }
    
}
