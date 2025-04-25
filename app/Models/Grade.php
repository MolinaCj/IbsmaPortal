<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'department_id', 'subject_id', 'semester', 'year_level', 'school_year', 'grade','incomplete','special','teacher_id']; 


    /**
     * Define relationship with Student model.
     */
    // public function student()
    // {
    //     return $this->belongsTo(Student::class, 'student_id', 'student_id');
    // }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }


    /**
     * Define relationship with Department model.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Define relationship with Subject model.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

        public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    
}
