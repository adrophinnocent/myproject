<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'duration_days',
        'duration_nights',
        'start_location',
        'end_location',
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
        'payment_terms',
        'terms_conditions',
        'cancellation_policy',
        'internal_costing',
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
        ];
    }
}
