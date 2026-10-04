<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomSafariInquiry extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_NEW = 'new';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_CONVERTED = 'converted';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'country',
        'adults',
        'children',
        'children_ages',
        'travel_date',
        'flexible_dates',
        'duration_days',
        'trip_type',
        'accommodation_preference',
        'budget_per_person',
        'travel_style',
        'group_type',
        'activities',
        'special_requests',
        'status',
        'proposal_id',
    ];

    protected function casts(): array
    {
        return [
            'activities' => 'array',
            'travel_date' => 'date',
            'flexible_dates' => 'boolean',
            'adults' => 'integer',
            'children' => 'integer',
            'duration_days' => 'integer',
        ];
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function getTotalTravelersAttribute(): int
    {
        return $this->adults + $this->children;
    }
}
