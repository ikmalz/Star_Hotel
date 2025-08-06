<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'payment_proof' => $this->payment_proof ? asset('storage/' . $this->payment_proof) : null,
            'payment_status' => $this->payment_status,
            'payment_date' => $this->payment_date,
            'refund_amount' => $this->refund_amount,
            'refunded_at' => $this->refunded_at,

            'booking' => $this->whenLoaded('booking', function () {
                return [
                    'id' => $this->booking->id,
                    'code_booking' => $this->booking->code_booking,
                    'status_booking' => $this->booking->status_booking,
                    'checkin_at' => $this->booking->checkin_at,
                    'checkout_at' => $this->booking->checkout_at,
                    'room_type' => [
                        'id' => $this->booking->roomType->id ?? null,
                        'name_type' => $this->booking->roomType->name_type ?? null,
                        'price_per_night' => $this->booking->roomType->price_per_night ?? null,
                    ]
                ];
            }),
        ];
    }
}
