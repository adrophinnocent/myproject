<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPlanMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_plan_id', 'sender_type', 'message', 'attachments', 'is_read'
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean'
    ];

    public function tripPlan()
    {
        return $this->belongsTo(TripPlan::class);
    }
}
