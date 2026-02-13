<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormerHeadmaster extends Model
{
    protected $fillable = [
        'name',
        'image',
        'joining_date',
        'leaving_date',
        'qualification',
        'rank',
        'is_active'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'leaving_date' => 'date',
    ];
}
