<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code_booking' => $this->code_booking,
            'status' => $this->status_booking,
            'checkin_at' => $this->checkin_at,
            'checkout_at' => $this->checkout_at,
            'price_total' => $this->price_total,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],

            'room_type' => [
                'id' => $this->roomType->id,
                'name' => $this->roomType->name_type,
                'capacity' => $this->roomType->capacity,
                'nightly_rate' => $this->roomType->nightly_rate,
            ],

            'payments' => PaymentResource::collection($this->whenLoaded('payments')),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
