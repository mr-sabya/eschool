<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order'
    ];

    public function routines()
    {
        return $this->hasMany(Routine::class);
    }
}
