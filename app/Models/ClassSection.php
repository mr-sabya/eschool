<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_class_id',
        'name',
        'numeric_name',
    ];

    // Relation to SchoolClass
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
