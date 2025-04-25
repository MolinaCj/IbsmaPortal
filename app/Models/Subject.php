<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'units', 'room', 'teacher_id', 'department_id', 'semester', 'year', 'prerequisite_id', 'major', 'schedule',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

      // This accessor automatically decodes the JSON string from the database
      public function getScheduleAttribute($value)
      {
          if (empty($value)) {
              return [];
          }
          return json_decode($value, true);
      }
  
      // This mutator automatically encodes the array to JSON string before saving
      public function setScheduleAttribute($value)
      {
          $this->attributes['schedule'] = is_array($value) ? json_encode($value) : $value;
      }


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'grades', 'subject_id', 'student_id')
                    ->withPivot('grade', 'year_level', 'semester')
                    ->withTimestamps();
    }

        public function prerequisite()
    {
        return $this->belongsTo(Subject::class, 'prerequisite_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id');
    }



}
