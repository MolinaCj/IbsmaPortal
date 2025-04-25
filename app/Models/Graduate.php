<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Graduate extends Model
{
    use HasFactory;

    protected $table = 'graduates'; // Explicitly define the table name

    protected $fillable = [
        'student_id', 'department_id', 'graduation_year','name'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
 