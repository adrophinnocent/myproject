<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPlan extends Model
{
    use HasFactory;

    const STATUS_NEW = 'new';

    const STATUS_REVIEWING = 'reviewing';

    const STATUS_SENT = 'sent';

    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name', 'email', 'phone', 'nationality',
        'destination_ids', 'travel_style', 'budget_range',
        'duration', 'accommodation_level', 'interests',
        'travel_date', 'group_size', 'adults', 'children',
        'message', 'status', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'destination_ids' => 'array',
            'interests' => 'array',
            'travel_date' => 'date',
        ];
    }
}
