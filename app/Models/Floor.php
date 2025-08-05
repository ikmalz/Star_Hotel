<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'floor_number',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function hotel()
    {
        return $this->hasOneThrough(Hotel::class, RoomType::class, 'id', 'id', 'room_type_id', 'hotel_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
