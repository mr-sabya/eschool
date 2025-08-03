<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'class_section_id',
        'shift_id',
        'status',
    ];

    // Relationships

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function items()
    {
        return $this->hasMany(SubjectAssignItem::class);
    }
}
