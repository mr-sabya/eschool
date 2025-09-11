<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
