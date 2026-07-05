<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'image', 'type', 'mime_type', 'page', 'media_id',
        'cta_text', 'cta_url', 'order', 'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order');
    }
}
