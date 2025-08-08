<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_session_id',
        'user_id',
        'roll_number',
        'school_class_id',
        'class_section_id',
        'department_id', // Added department_id field
        'shift_id',
        'guardian_id',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'admission_date',
        'admission_number',
        'category',
        'gender_id',
        'blood_id',
        'religion_id',
        'national_id',
        'place_of_birth',
        'nationality',
        'language',
        'health_status',
        'rank_in_family',
        'number_of_siblings',
        'profile_picture',
        'emergency_contact_name',
        'emergency_contact_phone',
        'previous_school_attended',
        'previous_school',
        'previous_school_document',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'previous_school_attended' => 'boolean',
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    # -------------------------------
    # RELATIONSHIPS
    # -------------------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guardian()
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function blood()
    {
        return $this->belongsTo(Blood::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
