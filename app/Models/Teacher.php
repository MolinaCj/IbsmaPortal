<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'civil_status',
        'password',
        'profile_picture',
        'department',
        'phoneNumber',
        'department_id', // FK from Dept table
        'otp', // Added for OTP authentication
        'otp_expires_at',
        'is_active', // Added for soft delete

        'login_attempts',
    'lockout_count',
    'lockout_time',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
        'lockout_time' => 'datetime',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id', 'id');
    }
    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'instructor_id');
    }
    public function grades()
    {
        return $this->hasMany(Grade::class, 'teacher_id', 'id');
    }

}
