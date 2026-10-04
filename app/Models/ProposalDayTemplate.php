<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalDayTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'destination',
        'starting_point',
        'ending_point',
        'route',
        'description',
        'activities',
        'highlights',
        'special_notes',
        'optional_activities',
        'distance',
        'driving_time',
        'transport_type',
        'meals',
        'accommodation_property',
        'room_type',
        'accommodation_location',
        'nights',
        'meal_plan',
        'cover_image',
        'gallery_images',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
            'nights' => 'integer',
        ];
    }
}
