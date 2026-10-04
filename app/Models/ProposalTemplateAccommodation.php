<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplateAccommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_template_id',
        'property_name',
        'location',
        'category',
        'room_type',
        'nights',
        'meal_plan',
        'description',
        'image',
        'website_url',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'nights' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function template()
    {
        return $this->belongsTo(ProposalTemplate::class, 'proposal_template_id');
    }
}
