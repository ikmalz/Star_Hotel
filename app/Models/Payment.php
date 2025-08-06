<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',             
        'payment_method',
        'payment_proof',
        'transaction_id',
        'va_number',
        'payment_status',
        'payment_date',
        'refund_amount',      
        'refunded_at',        
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isRefunded(): bool
    {
        return $this->payment_status === 'refunded';
    }
}
