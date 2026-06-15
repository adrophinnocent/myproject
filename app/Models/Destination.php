<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'description',
        'featured_image',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image
            ? asset('storage/'.$this->featured_image)
            : null;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
