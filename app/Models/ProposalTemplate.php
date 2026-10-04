<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplate extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'title',
        'subtitle',
        'duration_days',
        'duration_nights',
        'start_location',
        'end_location',
        'route_summary',
        'destinations',
        'safari_style',
        'accommodation_level',
        'transport_type',
        'is_private',
        'highlights',
        'itinerary',
        'inclusions',
        'exclusions',
        'optional_extras',
        'accommodations',
        'default_total_price',
        'default_adult_price',
        'default_child_price',
        'default_deposit_percentage',
        'payment_terms',
        'payment_methods',
        'payment_instructions',
        'terms_conditions',
        'cancellation_policy',
        'refund_policy',
        'internal_costing',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'destinations' => 'array',
            'highlights' => 'array',
            'itinerary' => 'array',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'optional_extras' => 'array',
            'accommodations' => 'array',
            'internal_costing' => 'array',
            'is_private' => 'boolean',
            'default_total_price' => 'decimal:2',
            'default_adult_price' => 'decimal:2',
            'default_child_price' => 'decimal:2',
            'default_deposit_percentage' => 'decimal:2',
        ];
    }

    // Relationships
    public function days()
    {
        return $this->hasMany(ProposalTemplateDay::class, 'proposal_template_id')->orderBy('day_number')->orderBy('sort_order');
    }

    public function templateAccommodations()
    {
        return $this->hasMany(ProposalTemplateAccommodation::class, 'proposal_template_id')->orderBy('sort_order');
    }

    public function templateInclusions()
    {
        return $this->hasMany(ProposalTemplateInclusion::class, 'proposal_template_id')->orderBy('sort_order');
    }

    public function templateExclusions()
    {
        return $this->hasMany(ProposalTemplateExclusion::class, 'proposal_template_id')->orderBy('sort_order');
    }

    public function templatePrice()
    {
        return $this->hasOne(ProposalTemplatePrice::class, 'proposal_template_id');
    }

    // Get structured itinerary array (from relational days if present, else JSON column)
    public function getStructuredItineraryAttribute(): array
    {
        if ($this->days()->count() > 0) {
            $structured = [];
            foreach ($this->days as $day) {
                $structured[] = [
                    'day' => $day->day_number,
                    'title' => $day->title,
                    'destination' => $day->destination,
                    'starting_point' => $day->starting_point,
                    'ending_point' => $day->ending_point,
                    'route' => $day->route,
                    'description' => $day->description,
                    'activities' => $day->activities,
                    'optional_activities' => $day->optional_activities,
                    'driving_time' => $day->driving_time,
                    'meals' => $day->meals,
                    'accommodation_property' => $day->accommodation_property,
                    'room_type' => $day->room_type,
                    'cover_image' => $day->cover_image,
                    'gallery_images' => $day->gallery_images ?? [],
                ];
            }
            return $structured;
        }

        return is_array($this->itinerary) ? $this->itinerary : [];
    }

    // Get structured accommodations array
    public function getStructuredAccommodationsAttribute(): array
    {
        if ($this->templateAccommodations()->count() > 0) {
            $accs = [];
            foreach ($this->templateAccommodations as $acc) {
                $accs[] = [
                    'property_name' => $acc->property_name,
                    'location' => $acc->location,
                    'category' => $acc->category,
                    'room_type' => $acc->room_type,
                    'nights' => $acc->nights,
                    'meal_plan' => $acc->meal_plan,
                    'description' => $acc->description,
                    'image' => $acc->image,
                    'website_url' => $acc->website_url,
                ];
            }
            return $accs;
        }

        return is_array($this->accommodations) ? $this->accommodations : [];
    }

    // Get structured inclusions list
    public function getStructuredInclusionsAttribute(): array
    {
        if ($this->templateInclusions()->count() > 0) {
            return $this->templateInclusions->pluck('item')->toArray();
        }

        return is_array($this->inclusions) ? $this->inclusions : [];
    }

    // Get structured exclusions list
    public function getStructuredExclusionsAttribute(): array
    {
        if ($this->templateExclusions()->count() > 0) {
            return $this->templateExclusions->pluck('item')->toArray();
        }

        return is_array($this->exclusions) ? $this->exclusions : [];
    }
}
