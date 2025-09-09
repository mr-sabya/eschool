<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_days', // optional: membership validity in days
    ];

    public function members()
    {
        return $this->hasMany(LibraryMember::class, 'member_category_id');
    }
}
