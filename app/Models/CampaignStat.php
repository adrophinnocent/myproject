<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignStat extends Model
{
    protected $fillable = [
        'campaign_id', 'type', 'ip_address', 'user_agent', 'referrer', 'platform'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
