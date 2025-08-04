<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'payment_method' => $this->payment_method,
            'payment_proof' => $this->payment_proof,
            'payment_status' => $this->payment_status,
            'payment_date' => $this->payment_date,
            'booking' => [
                'id' => $this->booking->id,
                'code_booking' => $this->booking->code_booking,
                'status_booking' => $this->booking->status_booking,
                'user' => [
                    'id' => $this->booking->user->id,
                    'name' => $this->booking->user->name,
                    'email' => $this->booking->user->email
                ]
            ]
        ];
    }
}
