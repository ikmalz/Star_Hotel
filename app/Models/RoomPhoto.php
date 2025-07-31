<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'photo'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
