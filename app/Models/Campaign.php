<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    protected $fillable = [
        'title', 'slug', 'type', 'description', 'itinerary',
        'price', 'currency', 'image', 'video_url', 'status',
        'tracking_id', 'meta_title', 'meta_description', 'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->tracking_id)) {
                $model->tracking_id = (string) Str::uuid();
            }
        });
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function stats()
    {
        return $this->hasMany(CampaignStat::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getPublicUrlAttribute()
    {
        return route('campaign.show', $this->slug) . '?cid=' . $this->tracking_id;
    }
}
