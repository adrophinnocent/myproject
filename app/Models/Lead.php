<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'campaign_id', 'name', 'email', 'phone', 'country',
        'travel_date', 'travelers_count', 'message', 'status'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
