<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplatePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_template_id',
        'currency',
        'subtotal_price',
        'discount_amount',
        'total_price',
        'adult_price',
        'child_price',
        'deposit_required',
        'deposit_percentage',
        'accommodation_cost',
        'park_fees',
        'vehicle_cost',
        'guide_cost',
        'meals_cost',
        'transfers_cost',
        'flights_cost',
        'activities_cost',
        'government_fees',
        'other_costs',
    ];

    protected function casts(): array
    {
        return [
            'subtotal_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_price' => 'decimal:2',
            'adult_price' => 'decimal:2',
            'child_price' => 'decimal:2',
            'deposit_required' => 'decimal:2',
            'deposit_percentage' => 'decimal:2',
            'accommodation_cost' => 'decimal:2',
            'park_fees' => 'decimal:2',
            'vehicle_cost' => 'decimal:2',
            'guide_cost' => 'decimal:2',
            'meals_cost' => 'decimal:2',
            'transfers_cost' => 'decimal:2',
            'flights_cost' => 'decimal:2',
            'activities_cost' => 'decimal:2',
            'government_fees' => 'decimal:2',
            'other_costs' => 'decimal:2',
        ];
    }

    public function template()
    {
        return $this->belongsTo(ProposalTemplate::class, 'proposal_template_id');
    }
}
