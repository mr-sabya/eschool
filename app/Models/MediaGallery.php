<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'type',
        'category',
        'is_active'
    ];
}
