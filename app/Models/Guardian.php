<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'phone',
        'address',
        'date_of_birth',
        'occupation',
        'national_id',
        'place_of_birth',
        'nationality',
        'language',
        'profile_picture',
        'is_active',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

}
