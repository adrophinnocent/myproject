<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Safari extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $slugSource = 'title';

    protected $fillable = [
        'category_id', 'destination_id', 'title', 'slug', 'short_description', 'description',
        'price', 'price_note', 'duration_days', 'duration_nights', 'group_size_min', 'group_size_max',
        'difficulty_level', 'accommodation_type', 'departure_location', 'itinerary', 'inclusions',
        'exclusions', 'faqs', 'featured_image', 'meta_title', 'meta_description', 'highlights',
        'child_price', 'group_discount', 'departure_time', 'what_to_bring', 'good_to_know',
        'video_url', 'meta_keywords', 'is_published', 'is_featured', 'is_bestseller', 'is_new',
        'availability_notes', 'min_age', 'max_age', 'languages_offered', 'safari_type',
        'seasonal_pricing', 'availability_dates', 'booking_deadline_days', 'sort_order', 'views_count',
        'location_name', 'latitude', 'longitude'
    ];

    protected $casts = [
        'itinerary' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'faqs' => 'array',
        'highlights' => 'array',
        'what_to_bring' => 'array',
        'languages_offered' => 'array',
        'seasonal_pricing' => 'array',
        'availability_dates' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_new' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 5.0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : asset('images/tour-placeholder.jpg');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 0);
    }

    public function getDurationTextAttribute(): string
    {
        return "{$this->duration_days} Days / " . ($this->duration_nights ?: ($this->duration_days - 1)) . " Nights";
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestseller(Builder $query): Builder
    {
        return $query->where('is_bestseller', true);
    }

    public function getNameAttribute()
    {
        return $this->title;
    }

    public function getTranslation(string $field): mixed
    {
        return $this->{$field};
    }
}
