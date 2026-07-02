<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'tour_id',
        'safari_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'nationality',
        'number_of_adults',
        'number_of_children',
        'travel_date',
        'special_requests',
        'accommodation_preference',
        'status',
        'total_price',
        'payment_status',
        'payment_method',
        'notes',
        'admin_notes',
        'confirmed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    const STATUS_PENDING = 'pending';

    const STATUS_CONFIRMED = 'confirmed';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            $booking->booking_reference = 'SST-'.strtoupper(substr(uniqid(), -8));
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

    public function getBookableItemAttribute()
    {
        return $this->tour ?? $this->safari;
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getTotalGuestsAttribute(): int
    {
        return $this->number_of_adults + $this->number_of_children;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => '<span class="badge badge-success">Confirmed</span>',
            'completed' => '<span class="badge badge-info">Completed</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-warning">Pending</span>',
        };
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
