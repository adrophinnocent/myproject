<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'link',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
