<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'category_level',
        'destination_id',
        'location_name',
        'description',
        'images',
        'star_rating',
        'supplier_cost_per_night',
        'amenities',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'is_active' => 'boolean',
        'star_rating' => 'decimal:1',
        'supplier_cost_per_night' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function itineraryDays()
    {
        return $this->hasMany(PackageItineraryDay::class);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if (is_array($this->images) && count($this->images) > 0) {
            $img = $this->images[0];
            if (str_starts_with($img, 'http')) return $img;
            return asset(str_starts_with($img, 'storage/') ? $img : 'storage/' . $img);
        }

        return asset('images/tour-placeholder.jpg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('name');
    }
}
