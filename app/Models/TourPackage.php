<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'safari_id',
        'name',
        'slug',
        'description',
        'selling_price',
        'adult_price',
        'child_price',
        'single_supplement',
        'deposit_percentage',
        'currency',
        'images',
        'sort_order',
        'is_active',
        // Private Internal Costs
        'supplier_hotel_cost',
        'park_fees_cost',
        'transport_cost',
        'guide_cost',
        'meals_cost',
        'transfers_cost',
        'other_costs',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'selling_price' => 'decimal:2',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'single_supplement' => 'decimal:2',
        'deposit_percentage' => 'decimal:2',
        'supplier_hotel_cost' => 'decimal:2',
        'park_fees_cost' => 'decimal:2',
        'transport_cost' => 'decimal:2',
        'guide_cost' => 'decimal:2',
        'meals_cost' => 'decimal:2',
        'transfers_cost' => 'decimal:2',
        'other_costs' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name . '-' . Str::random(5));
            }
        });
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class)->withTrashed();
    }

    public function safari()
    {
        return $this->belongsTo(Safari::class)->withTrashed();
    }

    public function itineraryDays()
    {
        return $this->hasMany(PackageItineraryDay::class)->orderBy('day_number');
    }

    public function roomOptions()
    {
        return $this->hasMany(PackageRoomOption::class);
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        $symbol = match (strtoupper($this->currency ?? 'USD')) {
            'EUR' => '€',
            'GBP' => '£',
            'TZS' => 'TSh ',
            default => '$',
        };

        return $symbol . number_format($this->selling_price, 2);
    }

    // INTERNAL PRIVATE COSTING COMPUTED ATTRIBUTES (Hidden from Guests)
    public function getTotalInternalCostAttribute(): float
    {
        return (float) ($this->supplier_hotel_cost +
            $this->park_fees_cost +
            $this->transport_cost +
            $this->guide_cost +
            $this->meals_cost +
            $this->transfers_cost +
            $this->other_costs);
    }

    public function getExpectedProfitAttribute(): float
    {
        return (float) ($this->selling_price - $this->total_internal_cost);
    }

    public function getProfitMarginPercentAttribute(): float
    {
        if ($this->selling_price <= 0) return 0.0;
        return round(($this->expected_profit / $this->selling_price) * 100, 1);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
