<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $slugSource = 'title';

    protected $fillable = [
        'title', 'slug', 'category_id', 'destination_id',
        'short_description', 'description', 'highlights',
        'price', 'price_note', 'child_price', 'group_discount',
        'duration_days', 'duration_nights',
        'group_size_min', 'group_size_max',
        'difficulty_level', 'accommodation_type',
        'departure_location', 'departure_time', 'meeting_point',
        'pickup_included', 'airport_pickup', 'transport_included',
        'map_location', 'assigned_guide', 'special_notes',
        'itinerary', 'inclusions', 'exclusions', 'faqs',
        'what_to_bring', 'good_to_know',
        'featured_image', 'video_url',
        'meta_title', 'meta_description', 'meta_keywords',
        'is_published', 'is_featured', 'is_bestseller', 'is_new',
        'limited_offer', 'sort_order', 'views_count',
        'start_date', 'end_date', 'available_slots',
        'seasonal_tag', 'availability_notes', 'min_age', 'max_age',
        'languages_offered', 'tour_type',
        'seasonal_pricing', 'availability_dates',
        'booking_deadline_days', 'deposit_percent', 'currency',
        'location_name', 'latitude', 'longitude',
    ];

    protected function casts(): array
    {
        return [
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
            'price' => 'decimal:2',
            'child_price' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function images()
    {
        return $this->hasMany(TourImage::class)->orderBy('sort_order');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function translations()
    {
        return $this->hasMany(TourTranslation::class);
    }

    public function getTranslation($field)
    {
        $locale = app()->getLocale();
        if ($locale === 'en') {
            return $this->{$field};
        }

        $translation = $this->translations->where('locale', $locale)->first();

        return $translation ? $translation->{$field} : $this->{$field};
    }

    // Scopes
    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }

    public function scopeBestseller($q)
    {
        return $q->where('is_bestseller', true);
    }

    public function scopeByCategory($q, $id)
    {
        return $q->where('category_id', $id);
    }

    public function scopeByType($q, $type)
    {
        return $q->where('tour_type', $type);
    }

    // Accessors
    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return asset('storage/'.$this->featured_image);
        }

        return 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&q=80';
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format($this->price, 0);
    }

    public function getDurationTextAttribute(): string
    {
        return "{$this->duration_days} Days / {$this->duration_nights} Nights";
    }

    public function getAverageRatingAttribute(): float
    {
        try {
            return round($this->reviews()->avg('rating') ?? 5.0, 1);
        } catch (\Exception $e) {
            return 5.0;
        }
    }

    public function getReviewCountAttribute(): int
    {
        try {
            return $this->reviews()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getCurrentPriceAttribute(): float
    {
        // Check seasonal pricing
        if ($this->seasonal_pricing) {
            $month = now()->month;
            foreach ($this->seasonal_pricing as $season) {
                if (isset($season['months']) && in_array($month, $season['months'])) {
                    return $season['price'];
                }
            }
        }

        return $this->price;
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getYouTubeEmbedUrlAttribute(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        $url = $this->video_url;
        
        // Extract YouTube video ID
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // If it's already an embed URL, return it
        if (str_contains($url, 'youtube.com/embed/')) {
            return $url;
        }

        return null;
    }
}
