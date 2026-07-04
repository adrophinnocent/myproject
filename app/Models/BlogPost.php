<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory, HasSlug, Translatable;

    protected $slugSource = 'title';

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'author_id',
        'excerpt',
        'content',
        'featured_image',
        'tags',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->featured_image)
            : asset('images/blog-placeholder.jpg');
    }

    public function getReadingTimeAttribute(): string
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);

        return $minutes.' min read';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }
}
