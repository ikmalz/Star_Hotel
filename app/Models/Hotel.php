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
        'city_id',
        'description',
        'image',
        'customer_service_phone',
    ];

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('value');
    }

    public function province()
    {
        return $this->hasOneThrough(
            Province::class,
            City::class,
            'id',
            'id',
            'city_id',
            'province_id'
        );
    }

  public function comment()
{
    return $this->hasMany(Comment::class);
}


}
