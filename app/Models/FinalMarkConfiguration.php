<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalMarkConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',   // ✅ added here
        'school_class_id',
        'department_id',
        'subject_id',
        'class_test_total',
        'other_parts_total',
        'final_result_weight_percentage',
        'grading_scale',
        'exclude_from_gpa'
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
