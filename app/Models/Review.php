<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['tour_id', 'name', 'email', 'country', 'rating', 'title', 'content', 'trip_date', 'is_approved', 'helpful_count'];

    protected function casts(): array
    {
        return ['is_approved' => 'boolean', 'rating' => 'integer', 'trip_date' => 'date'];
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function scopeApproved($q)
    {
        return $q->where('is_approved', true);
    }
}
