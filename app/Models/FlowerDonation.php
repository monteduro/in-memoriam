<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlowerDonation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id',
        'name',
        'flower_type',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
