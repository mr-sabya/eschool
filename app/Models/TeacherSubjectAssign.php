<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_subject_assign_id',
        'teacher_id',
        'subject_type_id',
        'classroom_id',
        'periods_per_week',
    ];

    public function classSubject()
    {
        return $this->belongsTo(ClassSubjectAssign::class, 'class_subject_assign_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subjectType()
    {
        return $this->belongsTo(SubjectType::class);
    }

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
