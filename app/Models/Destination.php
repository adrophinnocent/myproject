<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory, HasSlug, Translatable;

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

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function safaris(): HasMany
    {
        return $this->hasMany(\App\Models\Safari::class);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->featured_image) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->featured_image);
        }

        return 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&q=80';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getTotalItemsAttribute(): int
    {
        $tourCount = $this->tours()->where('is_published', true)->count();
        $safariCount = $this->safaris()->where('is_published', true)->count();
        return $tourCount + $safariCount;
    }
}
