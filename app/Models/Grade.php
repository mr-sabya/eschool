<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_name',
        'grade_point',
        'start_marks',
        'end_marks',
        'remarks',
        'grading_scale',
    ];
}
