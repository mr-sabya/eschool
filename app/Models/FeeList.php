<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeList extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_session_id',
        'fee_type_id',
        'school_class_id',
        'class_section_id',
        'name',
        'amount',
        'due_date',
        'is_active',
    ];

    /** -----------------------------
     * Relationships
     * -----------------------------*/

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }
}
