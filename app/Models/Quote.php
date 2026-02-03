<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'institution',
        'location',
        'message',
        'image',
        'order',
        'is_active'
    ];
}
