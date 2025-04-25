<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoginAttempt extends Model
{
    protected $fillable = [
        'username',
        'admin_id',
        'ip_address',
        'user_agent',
        'status',
    ];

    // Automatically cast date fields to Carbon instances
    protected $dates = ['created_at'];
}
