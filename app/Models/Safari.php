<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Safari extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'category_id', 'destination_id', 'title', 'slug', 'short_description', 'description',
        'price', 'price_note', 'duration_days', 'duration_nights', 'group_size_min', 'group_size_max',
        'difficulty_level', 'accommodation_type', 'departure_location', 'itinerary', 'inclusions',
        'exclusions', 'faqs', 'featured_image', 'meta_title', 'meta_description', 'highlights',
        'child_price', 'group_discount', 'departure_time', 'what_to_bring', 'good_to_know',
        'video_url', 'meta_keywords', 'is_published', 'is_featured', 'is_bestseller', 'is_new',
        'availability_notes', 'min_age', 'max_age', 'languages_offered', 'safari_type',
        'seasonal_pricing', 'availability_dates', 'booking_deadline_days', 'sort_order', 'views_count'
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function reviews()
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

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : asset('images/tour-placeholder.jpg');
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 0);
    }

    public function getDurationTextAttribute(): string
    {
        return "{$this->duration_days} Days / " . ($this->duration_nights ?: ($this->duration_days - 1)) . " Nights";
    }

    public function getTranslation($field)
    {
        return $this->{$field};
    }
}
