<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'form_path'
    ];
}
