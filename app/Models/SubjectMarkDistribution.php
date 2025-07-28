<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectMarkDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'class_section_id',
        'subject_id',
        'mark_distribution_id',
        'mark',
        'weight_percentage'
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function markDistribution()
    {
        return $this->belongsTo(MarkDistribution::class);
    }
}
