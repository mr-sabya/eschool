<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_class_id',
        'class_section_id',
        'shift_id',
        'department_id',
        'subject_id',
        'attendance_date',
        'status',
    ];

    // Relationships
    // student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    // school class
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    // class section
    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    // shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
