<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BookingPayment extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';

    const STATUS_COMPLETED = 'completed';

    const STATUS_FAILED = 'failed';

    const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'booking_id',
        'transaction_reference',
        'gateway',
        'payment_type',
        'amount',
        'currency',
        'status',
        'payer_name',
        'payer_email',
        'gateway_reference',
        'gateway_response',
        'notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            if (empty($payment->transaction_reference)) {
                $payment->transaction_reference = 'TXN-'.strtoupper(Str::random(10));
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
