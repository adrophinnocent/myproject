<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourTranslation extends Model
{
    protected $fillable = ['tour_id', 'locale', 'title', 'short_description', 'description', 'itinerary', 'highlights', 'inclusions', 'exclusions', 'faqs'];

    protected function casts(): array
    {
        return ['itinerary' => 'array', 'highlights' => 'array', 'inclusions' => 'array', 'exclusions' => 'array', 'faqs' => 'array'];
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
