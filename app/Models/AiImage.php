<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiImage extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'alt_text',
        'tags',
        'image_path',
        'original_image_path',
        'category',
        'related_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    protected $slugSource = 'title';

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function getOriginalImageUrlAttribute()
    {
        return $this->original_image_path ? asset('storage/' . $this->original_image_path) : null;
    }
}
