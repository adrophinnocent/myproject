<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_VIEWED = 'viewed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_CHANGES_REQUESTED = 'changes_requested';
    const STATUS_DECLINED = 'declined';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'token',
        'reference_code',
        'version',
        'tour_id',
        'custom_safari_inquiry_id',
        'client_name',
        'client_email',
        'client_phone',
        'country',
        'title',
        'subtitle',
        'welcome_message',
        'start_date',
        'end_date',
        'valid_until',
        'duration_days',
        'duration_nights',
        'adults',
        'children',
        'start_location',
        'end_location',
        'route_summary',
        'destinations',
        'safari_style',
        'accommodation_level',
        'transport_type',
        'is_private',
        'highlights',
        'currency',
        'subtotal_price',
        'discount_amount',
        'total_price',
        'price_per_person',
        'adult_price',
        'child_price',
        'child_age_range',
        'deposit_required',
        'deposit_percentage',
        'balance_amount',
        'balance_due_date',
        'payment_methods',
        'payment_instructions',
        'itinerary',
        'inclusions',
        'exclusions',
        'optional_extras',
        'accommodations',
        'payment_terms',
        'terms_conditions',
        'cancellation_policy',
        'refund_policy',
        'internal_costing',
        'status',
        'client_feedback',
        'signature_name',
        'accepted_email',
        'sent_at',
        'viewed_at',
        'first_viewed_at',
        'last_viewed_at',
        'view_count',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'itinerary' => 'array',
            'inclusions' => 'array',
            'exclusions' => 'array',
            'destinations' => 'array',
            'highlights' => 'array',
            'optional_extras' => 'array',
            'accommodations' => 'array',
            'internal_costing' => 'array',
            'is_private' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
            'valid_until' => 'date',
            'balance_due_date' => 'date',
            'sent_at' => 'datetime',
            'viewed_at' => 'datetime',
            'first_viewed_at' => 'datetime',
            'last_viewed_at' => 'datetime',
            'accepted_at' => 'datetime',
            'subtotal_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_price' => 'decimal:2',
            'price_per_person' => 'decimal:2',
            'adult_price' => 'decimal:2',
            'child_price' => 'decimal:2',
            'deposit_required' => 'decimal:2',
            'deposit_percentage' => 'decimal:2',
            'balance_amount' => 'decimal:2',
            'version' => 'integer',
            'view_count' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proposal) {
            if (empty($proposal->token)) {
                $proposal->token = Str::random(32);
            }

            if (empty($proposal->reference_code)) {
                $proposal->reference_code = static::generateNextReferenceCode();
            }

            if (empty($proposal->version)) {
                $proposal->version = 1;
            }
        });
    }

    public static function generateNextReferenceCode(): string
    {
        $year = date('Y');
        $lastProposal = static::where('reference_code', 'like', "TW-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastProposal && preg_match('/TW-\d{4}-(\d+)/', $lastProposal->reference_code, $matches)) {
            $nextNum = (int) $matches[1] + 1;
        } else {
            $nextNum = 1;
        }

        return sprintf('TW-%s-%04d', $year, $nextNum);
    }

    public function getFullReferenceAttribute(): string
    {
        $ref = $this->reference_code ?: ('TW-' . date('Y') . '-' . sprintf('%04d', $this->id));
        return $ref . '-V' . ($this->version ?: 1);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function customSafariInquiry()
    {
        return $this->belongsTo(CustomSafariInquiry::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('proposal.show', $this->token);
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

    public function getFormattedDepositRequiredAttribute(): string
    {
        return $this->currency_symbol . number_format($this->deposit_required ?? 0, 2);
    }

    public function getFormattedBalanceAmountAttribute(): string
    {
        $bal = $this->balance_amount ?? max(0, ($this->total_price ?? 0) - ($this->deposit_required ?? 0));
        return $this->currency_symbol . number_format($bal, 2);
    }

    public function getCalculatedBalanceAmountAttribute(): float
    {
        return (float) ($this->balance_amount ?? max(0, ($this->total_price ?? 0) - ($this->deposit_required ?? 0)));
    }

    public function getCalculatedDepositAmountAttribute(): float
    {
        if ($this->deposit_required && $this->deposit_required > 0) {
            return (float) $this->deposit_required;
        }

        $pct = $this->deposit_percentage ?? 30.0;
        return round((($this->total_price ?? 0) * $pct) / 100, 2);
    }

    public function getRouteChainAttribute(): string
    {
        if (!empty($this->route_summary)) {
            return $this->route_summary;
        }

        if (is_array($this->itinerary) && count($this->itinerary) > 0) {
            $points = [];
            if ($this->start_location) {
                $points[] = $this->start_location;
            }

            foreach ($this->itinerary as $day) {
                if (!empty($day['destination']) && !in_array($day['destination'], $points)) {
                    $points[] = $day['destination'];
                }
            }

            if ($this->end_location && !in_array($this->end_location, $points)) {
                $points[] = $this->end_location;
            }

            if (count($points) > 1) {
                return implode(' → ', $points);
            }
        }

        return ($this->start_location ?: 'Arusha') . ' → ' . ($this->end_location ?: 'Zanzibar');
    }

    public function getWhatsappMessageUrlAttribute(): string
    {
        $text = "Hello {$this->client_name}, your personalized Tanzania safari proposal from Twina Safaris is ready. Please review your itinerary, accommodation and pricing here: " . $this->public_url;
        $phone = preg_replace('/[^0-9]/', '', $this->client_phone ?? '');
        if (!empty($phone)) {
            return "https://api.whatsapp.com/send?phone={$phone}&text=" . urlencode($text);
        }
        return "https://api.whatsapp.com/send?text=" . urlencode($text);
    }

    public function getTotalTravelersAttribute(): int
    {
        return (int) $this->adults + (int) $this->children;
    }

    public function getTotalInternalCostAttribute(): float
    {
        $costing = $this->internal_costing ?? [];
        if (empty($costing)) {
            return 0.00;
        }

        if (isset($costing['total_cost']) && is_numeric($costing['total_cost']) && $costing['total_cost'] > 0) {
            return (float) $costing['total_cost'];
        }

        $accommodation = (float) ($costing['accommodation_cost'] ?? $costing['accommodation_costs'] ?? 0);
        $park = (float) ($costing['park_fees'] ?? 0);
        $vehicle = (float) ($costing['vehicle_cost'] ?? $costing['vehicle_costs'] ?? 0);
        $guide = (float) ($costing['guide_cost'] ?? $costing['guide_costs'] ?? 0);
        $meals = (float) ($costing['meals_cost'] ?? 0);
        $transfers = (float) ($costing['transfers_cost'] ?? 0);
        $flights = (float) ($costing['flights_cost'] ?? 0);
        $activities = (float) ($costing['activities_cost'] ?? $costing['activities_costs'] ?? 0);
        $government = (float) ($costing['government_fees'] ?? 0);
        $other = (float) ($costing['other_costs'] ?? $costing['other_expenses'] ?? 0);

        return $accommodation + $park + $vehicle + $guide + $meals + $transfers + $flights + $activities + $government + $other;
    }

    public function getCalculatedProfitAttribute(): float
    {
        return (float) ($this->total_price ?? 0) - $this->total_internal_cost;
    }

    public function getCalculatedProfitMarginAttribute(): float
    {
        $sellingPrice = (float) ($this->total_price ?? 0);
        if ($sellingPrice <= 0) {
            return 0.0;
        }

        return round(($this->calculated_profit / $sellingPrice) * 100, 2);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'bg-gray-100 text-gray-800 border-gray-300',
            self::STATUS_SENT => 'bg-emerald-50 text-emerald-900 border-emerald-300',
            self::STATUS_VIEWED => 'bg-[#052010] text-[#D4AF37] border-[#D4AF37]/40',
            self::STATUS_ACCEPTED => 'bg-emerald-100 text-emerald-900 border-emerald-400 font-extrabold',
            self::STATUS_CHANGES_REQUESTED => 'bg-amber-100 text-amber-900 border-amber-300',
            self::STATUS_DECLINED => 'bg-rose-100 text-rose-800 border-rose-300',
            self::STATUS_EXPIRED => 'bg-neutral-100 text-neutral-600 border-neutral-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
