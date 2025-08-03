<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssignItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_assign_id',
        'subject_id',
        'teacher_id',
    ];

    // Relationships

    public function subjectAssign()
    {
        return $this->belongsTo(SubjectAssign::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
