<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    // ✅ Scope or static method to get current session
    public static function current()
    {
        return self::where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->first();
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
