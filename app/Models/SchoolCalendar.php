<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolCalendar extends Model
{
    use HasFactory;

    protected $table = 'school_calendars'; // Specify the table name

    protected $fillable = [
        'semester',
        'sy',
        'image', // Allow mass assignment for image path
        'pdf',
        'word',
    ];
}
