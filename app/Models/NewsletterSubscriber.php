<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'active', 'subscribed_at', 'confirmation_token', 'confirmed_at', 'unsubscribe_token'];

    protected $casts = [
        'active' => 'boolean',
        'subscribed_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (NewsletterSubscriber $subscriber) {
            if (empty($subscriber->unsubscribe_token)) {
                do {
                    $subscriber->unsubscribe_token = Str::random(48);
                } while (static::where('unsubscribe_token', $subscriber->unsubscribe_token)->exists());
            }
        });
    }

    public function isConfirmed(): bool
    {
        return $this->active && $this->confirmed_at !== null;
    }

    public function getUnsubscribeUrlAttribute(): string
    {
        return route('newsletter.unsubscribe', $this->unsubscribe_token);
    }
}
