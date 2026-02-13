<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoverningBody extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'image',
        'rank',
        'mobile',
        'type',
        'is_active'
    ];
}
