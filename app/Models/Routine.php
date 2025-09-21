<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_session_id', // Add this
        'type',
        'day_id',
        'time_slot_id',
        'school_class_id',
        'class_section_id',
        'department_id', // <-- ADD THIS
        'subject_id',
        'staff_id',
    ];

    // Add the new relationship
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    // ... your other existing relationships (day, timeSlot, etc.)
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
