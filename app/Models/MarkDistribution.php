<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarkDistribution extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    // subjectmarkdistributions relationship
    public function subjectMarkDistributions()
    {
        return $this->hasMany(SubjectMarkDistribution::class);
    }
}
