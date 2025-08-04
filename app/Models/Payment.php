<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_method',
        'payment_proof',
        'transaction_id',
        'va_number',
        'payment_status',
        'payment_date',
    ];

    
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
