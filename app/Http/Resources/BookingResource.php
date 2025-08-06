<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code_booking' => $this->code_booking,
            'status_booking' => $this->status_booking,
            'checkin_at' => $this->checkin_at,
            'checkout_at' => $this->checkout_at,
            'price_total' => $this->price_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'room_type' => $this->whenLoaded('roomType', function () {
                return [
                    'id' => $this->roomType->id,
                    'name_type' => $this->roomType->name_type,
                    'price_per_night' => $this->roomType->price_per_night ?? null,
                ];
            }),

            'room' => $this->whenLoaded('room', function () {
                return [
                    'id' => $this->room->id,
                    'room_number' => $this->room->room_number,
                    'floor' => $this->room->floor,
                ];
            }),

            'latest_payment' => $this->whenLoaded('latestPayment', function () {
                return [
                    'id' => $this->latestPayment->id,
                    'amount' => $this->latestPayment->amount,
                    'payment_method' => $this->latestPayment->payment_method,
                    'payment_status' => $this->latestPayment->payment_status,
                    'payment_date' => $this->latestPayment->payment_date,
                    'refund_amount' => $this->latestPayment->refund_amount,
                    'refunded_at' => $this->latestPayment->refunded_at,
                ];
            }),

            'city' => $this->whenLoaded('city', function () {
                return [
                    'id' => $this->city->id,
                    'name' => $this->city->name,
                ];
            }),

            'floor' => $this->whenLoaded('floor', function () {
                return [
                    'id' => $this->floor->id,
                    'floor_number' => $this->floor->floor_number,
                ];
            }),
        ];
    }
}
