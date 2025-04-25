<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorLoginAttempt extends Model
{
    protected $fillable = [
        'email', 'ip_address', 'user_agent', 'attempted_at', 'status',
    ];

    // Automatically cast date fields to Carbon instances
    protected $dates = ['attempted_at'];

    protected static function booted()
    {
        static::creating(function ($attempt) {
            $last = static::latest()->first();

            // Avoid duplicate record only if the last one matches all fields
            if (
                $last &&
                $last->email === $attempt->email &&
                $last->ip_address === $attempt->ip_address &&
                $last->user_agent === $attempt->user_agent &&
                $last->status === $attempt->status &&
                $last->created_at->diffInSeconds(now()) < 10 // Optional: only prevent if within 10 seconds
            ) {
                return false; // Prevent saving duplicate
            }
        });
    }

}
