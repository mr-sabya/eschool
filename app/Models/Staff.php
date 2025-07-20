<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'department_id',
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'phone',
        'nid',
        'date_of_birth',
        'current_address',
        'permanent_address',
        'designation_id',
        'gender_id',
        'marital_status',
        'basic_salary',
        'date_of_joining',
        'profile_picture',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    // Relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}
