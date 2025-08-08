<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_address',
        'school_email',
        'school_phone',
        'school_history',
        'eiin_no',
        'school_code',
        'registration_no',
        'logo',
        'favicon',
        'copyright',
        'timezone',
    ];
}
