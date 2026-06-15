<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'active', 'subscribed_at'];

    protected $casts = [
        'active' => 'boolean',
        'subscribed_at' => 'datetime',
    ];
}
