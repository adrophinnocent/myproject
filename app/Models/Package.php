<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'duration',
        'price',
        'description',
        'short_description',
        'image',
        'is_bestseller',
        'is_popular',
        'is_new',
        'is_limited',
        'max_group_size',
        'difficulty',
        'destination_id',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
