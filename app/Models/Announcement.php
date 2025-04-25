<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements'; // Specify the table name

    protected $fillable = [
        'title',
        'description',
        'word',
        'pdf',
    ];

    public function images()
    {
        return $this->hasMany(AnnouncementImage::class);
    }

}
