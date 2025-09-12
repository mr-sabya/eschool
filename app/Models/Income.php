<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_head_id',
        'amount',
        'date',
        'payment_method_id',
        'note',
        'created_by',
        'invoice_number',   // ✅ new
        'attachment',       // ✅ new
    ];

    public function head()
    {
        return $this->belongsTo(IncomeHead::class, 'income_head_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
