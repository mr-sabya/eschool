<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_class_id',
        'class_section_id',
        'department_id', // Added department_id field
        'subject_id',
        'exam_id',
        'mark_distribution_id',
        'marks_obtained',
        'is_absent',
    ];

    // Relationships

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function markDistribution()
    {
        return $this->belongsTo(MarkDistribution::class);
    }
}
