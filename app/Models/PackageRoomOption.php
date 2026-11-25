<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRoomOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_package_id',
        'room_type_id',
        'supplement_price',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'supplement_price' => 'decimal:2',
    ];

    public function tourPackage()
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
