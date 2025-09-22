<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoutine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exam_id',
        'school_class_id',
        'class_section_id',
        'department_id',
        'subject_id',
        'time_slot_id',
        'exam_date',
        'class_room_id', // Replaced room_number
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * This ensures the 'exam_date' is always a Carbon date object for easy manipulation.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exam_date' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Defines the relationships this model has with other models.
    |
    */

    /**
     * Get the parent exam that this routine belongs to.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the class associated with this exam routine.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Get the section associated with this exam routine.
     */
    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    /**
     * Get the department associated with this exam routine (if any).
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the subject for this exam routine.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the time slot for this exam routine.
     */
    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    /**
     * Get the assigned classroom for this exam routine.
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
