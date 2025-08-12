<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'floor_id',
        'room_type_id',
        'room_id',
        'checkin_at',
        'checkout_at',
        'nights',
        'price_per_night',
        'price_total',
        'refund_requested',
        'status_booking',
        'code_booking',
    ];


    protected $casts = [
        'checkin_at' => 'datetime',
        'checkout_at' => 'datetime',
        'refund_requested' => 'boolean'
    ];


    public function hotel()
{
    return $this->belongsTo(\App\Models\Hotel::class, 'hotel_id');
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function province()
    {
        return $this->city?->province();
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status_booking) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'paid' => 'bg-green-100 text-green-700',
            'checked_in' => 'bg-blue-100 text-blue-700',
            'checkout_pending' => 'bg-orange-100 text-orange-700',
            'checked_out' => 'bg-gray-200 text-gray-700',
            'canceled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function calculateNights(): int
    {
        return Carbon::parse($this->checkin_at)->diffInDays(Carbon::parse($this->checkout_at));
    }

    public function calculateTotalPrice(): int
    {
        return $this->price_per_night * $this->nights;
    }

    public function isFullyPaid(): bool
    {
        $totalPaid = $this->payments()->where('payment_status', 'paid')->sum('amount');
        return $totalPaid >= $this->price_total;
    }
}
