<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'name',
        'email',
        'phone',
        'message',
        'status'
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
