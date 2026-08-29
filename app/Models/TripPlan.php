<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPlan extends Model
{
    use HasFactory;

    const STATUS_NEW = 'new';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_PREPARING = 'preparing_plan';
    const STATUS_SENT = 'sent';
    const STATUS_VIEWED = 'viewed';
    const STATUS_CHANGES_REQUESTED = 'changes_requested';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_BOOKING = 'booking';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CLOSED = 'closed';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'name', 'email', 'phone', 'nationality',
        'destination_ids', 'travel_style', 'budget_range',
        'duration', 'accommodation_level', 'interests',
        'travel_date', 'group_size', 'adults', 'children',
        'message', 'status', 'admin_notes',
        'trip_title', 'end_date', 'price_per_person', 'total_price',
        'discount_amount', 'tax_amount', 'deposit_amount', 'balance_amount',
        'currency', 'itinerary_data', 'inclusions_data', 'exclusions_data',
        'terms_conditions', 'sent_at', 'viewed_at', 'accepted_at',
        'declined_at', 'payment_status', 'booking_id'
    ];

    protected function casts(): array
    {
        return [
            'destination_ids' => 'array',
            'interests' => 'array',
            'itinerary_data' => 'array',
            'inclusions_data' => 'array',
            'exclusions_data' => 'array',
            'travel_date' => 'date',
            'end_date' => 'date',
            'sent_at' => 'datetime',
            'viewed_at' => 'datetime',
            'accepted_at' => 'datetime',
            'declined_at' => 'datetime',
            'price_per_person' => 'decimal:2',
            'total_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'balance_amount' => 'decimal:2',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function messages()
    {
        return $this->hasMany(TripPlanMessage::class);
    }
}
