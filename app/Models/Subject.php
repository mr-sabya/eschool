<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_fourth_subject', // Indicates if this is the 4th subject
    ];


    public function subjectMarkDistributions()
    {
        return $this->hasMany(SubjectMarkDistribution::class);
    }
}
