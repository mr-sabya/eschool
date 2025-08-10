<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSubjectAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_session_id',
        'school_class_id',
        'class_section_id',
        'department_id',
        'subject_id',
        'is_added_to_result',
    ];

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

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherSubjectAssign::class, 'class_subject_assign_id');
    }
    
}
