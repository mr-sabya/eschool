<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'book_category_id',
        'publisher',
        'published_year',
        'quantity',
        'available_quantity',
        'shelf_location',
        'cover_image'
    ];

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }
}
