<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickLink extends Model
{
    use HasFactory;

    protected $fillable = ['link_category_id', 'title', 'url', 'order', 'is_active'];

    /**
     * Relationship: Link belongs to a category
     */
    public function linkCategory()
    {
        return $this->belongsTo(LinkCategory::class);
    }
}
