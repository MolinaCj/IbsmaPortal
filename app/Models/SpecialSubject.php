<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialSubject extends Model
{
    use HasFactory;

    // Define the table name (optional, as Laravel will infer the name from the model)
    protected $table = 'special_subjects';

    // Define the fillable attributes
    protected $fillable = [
        'name', 'description', 'units', 'day', 'time', 'room', 'subject_id', 'teacher_id', 'department_id', 'semester', 'year','start_time','end_time','schedule',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];
    

    // Define relationships with other models

    /**
     * Get the regular subject that this special subject belongs to.
     */
    public function students()
{
    return $this->hasMany(Student::class);
}

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    /**
     * Get the teacher assigned to this special subject.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    /**
     * Get the department that this special subject belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id', 'subject_id')
                    ->where('special', 1); // Special subjects only
    }
}
