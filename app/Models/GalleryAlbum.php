<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'cover_image', 'is_published', 'sort_order'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'album_id')->orderBy('sort_order');
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover_image ? asset('storage/'.$this->cover_image) : null;
    }
}
