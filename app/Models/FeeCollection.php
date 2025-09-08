<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'fee_list_id',
        'school_class_id',
        'class_section_id',
        'amount_paid',
        'discount',
        'fine',
        'balance',
        'payment_date',
        'payment_method_id',
        'transaction_no',
        'note',
    ];

    /** -----------------------------
     * Relationships
     * ----------------------------- */

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeList()
    {
        return $this->belongsTo(FeeList::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
