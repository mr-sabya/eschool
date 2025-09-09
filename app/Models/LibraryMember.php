<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'user_type',
        'user_id',
        'member_category_id',
        'join_date',
        'expire_date',
        'status',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issues()
    {
        return $this->hasMany(BookIssue::class, 'member_id');
    }

    // Helper: check if membership is expired
    public function isExpired(): bool
    {
        return $this->expire_date && $this->expire_date < now()->toDateString();
    }

    public function category()
    {
        return $this->belongsTo(MemberCategory::class, 'member_category_id');
    }
}
