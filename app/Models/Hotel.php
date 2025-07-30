<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_hotel',
        'address',
        'city',
        'province',
        'description',
        'image',
        'customer_service_phone'
    ];

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }


    // public function ratings() {
    //     return $this->hasMany(Rating::class);
    // }

    // public function comments() {
    //     return $this->hasMany(Comment::class);
    // }
}
