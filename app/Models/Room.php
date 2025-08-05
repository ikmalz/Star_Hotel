<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'room_number',
        'floor_id',
        'status',
        'notes'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function photos()
    {
        return $this->hasMany(RoomPhoto::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}
