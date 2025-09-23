<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_session_id',
        'exam_category_id',
        'start_at',
        'end_at',
    ];


    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function examCategory()
    {
        return $this->belongsTo(ExamCategory::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * The mark distribution types that are allowed for this exam.
     */
    public function markDistributionTypes()
    {
        return $this->belongsToMany(MarkDistribution::class, 'exam_mark_distribution');
    }
}
