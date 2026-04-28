<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkCategory extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image', 'order', 'is_active'];

    /**
     * Relationship: One category has many links
     */
    public function links()
    {
        return $this->hasMany(QuickLink::class)->where('is_active', true)->orderBy('order', 'asc');
    }
}
