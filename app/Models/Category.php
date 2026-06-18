<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasSlug, Translatable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
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
