<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'birth_date',
        'death_date',
        'location',
        'biography',
        'key_traits'
    ];

    protected $casts = [
        'key_traits' => 'array',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function flowers()
    {
        return $this->hasMany(FlowerDonation::class);
    }
}
