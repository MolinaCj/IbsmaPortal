<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Allow mass assignment for the 'name' field

    // Define relationship with subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'department_id', 'id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'department_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'department_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'department_id', 'id');
    }

    public function graduates()
    {
        return $this->hasMany(Graduate::class, 'department_id', 'id');
    }


}
