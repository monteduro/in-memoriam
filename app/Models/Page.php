<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'birth_date',
        'death_date',
        'location',
        'biography',
        'key_traits',
        'image_path',
    ];

    protected $casts = [
        'key_traits' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($page) {
            // Controlla se l'immagine è stata scollegata
            if ($page->isDirty('image_path') && $page->getOriginal('image_path') !== $page->image_path) {
                // Cancella l'immagine dal disco se esiste
                if ($page->getOriginal('image_path')) {
                    Storage::delete($page->getOriginal('image_path'));
                }
            }
        });
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function flowers()
    {
        return $this->hasMany(FlowerDonation::class);
    }
}
