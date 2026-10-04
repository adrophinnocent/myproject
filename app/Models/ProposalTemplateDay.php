<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplateDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_template_id',
        'day_number',
        'title',
        'destination',
        'starting_point',
        'ending_point',
        'route',
        'description',
        'activities',
        'optional_activities',
        'driving_time',
        'meals',
        'accommodation_property',
        'room_type',
        'cover_image',
        'gallery_images',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
            'day_number' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function template()
    {
        return $this->belongsTo(ProposalTemplate::class, 'proposal_template_id');
    }
}
