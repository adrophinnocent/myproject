<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageItineraryDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_package_id',
        'day_number',
        'title',
        'description',
        'accommodation_id',
        'accommodation_notes',
        'meals',
        'sort_order',
    ];

    public function tourPackage()
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}
