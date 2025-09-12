<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_head_id',
        'amount',
        'date',
        'payment_method_id',
        'note',
        'created_by',
        'invoice_number', // new field
        'attachment',     // new field
    ];

    public function head()
    {
        return $this->belongsTo(ExpenseHead::class, 'expense_head_id');
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
