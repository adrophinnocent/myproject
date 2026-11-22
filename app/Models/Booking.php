<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DEPOSIT_PAID = 'deposit_paid';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_DEPOSIT_PAID = 'deposit_paid';
    const PAYMENT_FULLY_PAID = 'fully_paid';
    const PAYMENT_REFUNDED = 'refunded';

    protected $fillable = [
        'booking_reference',
        'token',
        'tour_id',
        'safari_id',
        'trip_plan_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'nationality',
        'number_of_adults',
        'number_of_children',
        'children_ages',
        'travel_date',
        'end_date',
        'flexible_dates',
        'special_requests',
        'accommodation_preference',
        'status',
        'total_price',
        'currency',
        'deposit_percentage',
        'deposit_required',
        'deposit_paid',
        'balance_amount',
        'balance_due_date',
        'payment_status',
        'payment_method',
        'notes',
        'admin_notes',
        'cancellation_reason',
        'selected_extras',
        'extras_total',
        'approved_at',
        'confirmed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'selected_extras' => 'array',
            'extras_total' => 'decimal:2',
            'travel_date' => 'date',
            'end_date' => 'date',
            'balance_due_date' => 'date',
            'approved_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'total_price' => 'decimal:2',
            'deposit_percentage' => 'decimal:2',
            'deposit_required' => 'decimal:2',
            'deposit_paid' => 'decimal:2',
            'balance_amount' => 'decimal:2',
            'flexible_dates' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->token)) {
                $booking->token = Str::random(32);
            }

            if (empty($booking->booking_reference)) {
                $booking->booking_reference = static::generateNextReferenceCode();
            }

            if (empty($booking->currency)) {
                $booking->currency = 'USD';
            }

            if (empty($booking->deposit_percentage)) {
                $booking->deposit_percentage = 20.00;
            }
        });
    }

    public static function generateNextReferenceCode(): string
    {
        $year = date('Y');
        $lastBooking = static::where('booking_reference', 'like', "TWN-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastBooking && preg_match('/TWN-\d{4}-(\d+)/', $lastBooking->booking_reference, $matches)) {
            $nextNum = (int) $matches[1] + 1;
        } else {
            $nextNum = 1;
        }

        return sprintf('TWN-%s-%03d', $year, $nextNum);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class)->withTrashed();
    }

    public function safari()
    {
        return $this->belongsTo(Safari::class)->withTrashed();
    }

    public function tripPlan()
    {
        return $this->belongsTo(TripPlan::class);
    }

    public function payments()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('booking_payments')) {
                return $this->hasMany(BookingPayment::class);
            }
        } catch (\Throwable $e) {
            // Ignore if table check fails
        }

        return $this->hasMany(static::class, 'id', 'id')->whereRaw('1 = 0');
    }

    public function getBookableItemAttribute()
    {
        if ($this->tour) return $this->tour;
        if ($this->safari) return $this->safari;

        if ($this->tripPlan) {
            return (object) [
                'title' => $this->tripPlan->trip_title ?: 'Custom Safari: ' . $this->tripPlan->name,
                'featured_image_url' => asset('images/banners/hero_fallback.webp'),
                'duration_text' => $this->tripPlan->duration ?: 'Custom Duration',
                'formatted_price' => '$' . number_format($this->tripPlan->total_price ?? 0, 0),
                'category' => (object) ['name' => 'Custom Trip'],
                'destination' => (object) ['name' => 'Tanzania'],
                'item_type' => 'custom',
                'itinerary' => $this->tripPlan->itinerary_data ?? []
            ];
        }

        return null;
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getTotalGuestsAttribute(): int
    {
        return (int)$this->number_of_adults + (int)$this->number_of_children;
    }

    public function getCurrencySymbolAttribute(): string
    {
        return match (strtoupper($this->currency ?? 'USD')) {
            'EUR' => '€',
            'GBP' => '£',
            'TZS' => 'TSh ',
            'UGX' => 'USh ',
            'KES' => 'KSh ',
            default => '$',
        };
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return $this->currency_symbol . number_format($this->total_price ?? 0, 2);
    }

    public function getCalculatedDepositRequiredAttribute(): float
    {
        if ($this->deposit_required && $this->deposit_required > 0) {
            return (float) $this->deposit_required;
        }

        $pct = $this->deposit_percentage ?? 20.00;
        return round((($this->total_price ?? 0) * $pct) / 100, 2);
    }

    public function getFormattedDepositRequiredAttribute(): string
    {
        return $this->currency_symbol . number_format($this->calculated_deposit_required, 2);
    }

    public function getCalculatedBalanceAmountAttribute(): float
    {
        $total = (float) ($this->total_price ?? 0);
        $paid = (float) ($this->deposit_paid ?? 0);
        return max(0, $total - $paid);
    }

    public function getFormattedBalanceAmountAttribute(): string
    {
        return $this->currency_symbol . number_format($this->calculated_balance_amount, 2);
    }

    public function getConfirmationUrlAttribute(): string
    {
        return route('booking.confirmation', $this->booking_reference);
    }

    public function getPaymentUrlAttribute(): string
    {
        return route('booking.payment', $this->booking_reference);
    }

    public function getTrackingUrlAttribute(): string
    {
        return route('booking.status', $this->booking_reference);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => '<span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-xs uppercase">Confirmed</span>',
            self::STATUS_DEPOSIT_PAID => '<span class="px-3 py-1 bg-amber-100 text-amber-900 rounded-full font-bold text-xs uppercase">Deposit Paid</span>',
            self::STATUS_APPROVED => '<span class="px-3 py-1 bg-sky-100 text-sky-800 rounded-full font-bold text-xs uppercase">Approved (Ready for Deposit)</span>',
            self::STATUS_COMPLETED => '<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-bold text-xs uppercase">Completed</span>',
            self::STATUS_CANCELLED => '<span class="px-3 py-1 bg-rose-100 text-rose-800 rounded-full font-bold text-xs uppercase">Cancelled</span>',
            default => '<span class="px-3 py-1 bg-stone-100 text-stone-800 rounded-full font-bold text-xs uppercase">Pending Approval</span>',
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
