<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'roomType', 'payments'])->latest()->get();
        return BookingResource::collection($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'checkin_at' => 'required|date',
            'checkout_at' => 'required|date|after:checkin_at',
            'price_total' => 'required|integer',
        ]);

        $userId = $request->user()->id;

        $existingBooking = Booking::where('user_id', $userId)
            ->whereIn('status_booking', ['pending', 'paid'])
            ->first();

        if ($existingBooking) {
            return response()->json([
                'message' => 'Anda masih memiliki booking yang belum diselesaikan.',
                'booking_id' => $existingBooking->id,
                'status_booking' => $existingBooking->status_booking
            ], 422);
        }

        $code = 'BK-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'user_id' => $userId,
            'room_type_id' => $request->room_type_id,
            'room_id' => null,
            'checkin_at' => $request->checkin_at,
            'checkout_at' => $request->checkout_at,
            'status_booking' => 'pending',
            'code_booking' => $code,
            'price_total' => $request->price_total,
        ]);

        return (new BookingResource($booking->load('roomType')))
            ->additional(['message' => 'Booking berhasil dibuat, silakan lakukan pembayaran.']);
    }


    public function show($id)
    {
        $booking = Booking::with(['user', 'roomType', 'payments'])->findOrFail($id);
        return new BookingResource($booking);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status_booking' => 'nullable|in:pending,paid,checked_in,checked_out,canceled',
            'room_id' => 'nullable|exists:rooms,id',
            'checkin_at' => 'nullable|date',
            'checkout_at' => 'nullable|date|after:checkin_at',
        ]);

        $oldStatus = $booking->status_booking;
        $oldRoomId = $booking->room_id;

        if ($request->filled('room_id')) {
            $room = Room::find($request->room_id);

            if ($room->room_type_id !== $booking->room_type_id) {
                return response()->json([
                    'message' => 'Room yang dipilih tidak sesuai dengan room type booking ini.'
                ], 422);
            }

            if ($room->status !== 'available') {
                return response()->json([
                    'message' => 'Room tidak tersedia (sudah dibooking atau maintenance).'
                ], 422);
            }

            $room->update(['status' => 'booked']);

            if ($oldRoomId && $oldRoomId != $room->id) {
                Room::where('id', $oldRoomId)->update(['status' => 'available']);
            }
        }

        $booking->update($request->only([
            'status_booking',
            'room_id',
            'checkin_at',
            'checkout_at'
        ]));

        if ($request->status_booking === 'checked_out' && $booking->room_id) {
            Room::where('id', $booking->room_id)->update(['status' => 'available']);
        }

        if ($request->status_booking === 'canceled' && $booking->room_id) {
            Room::where('id', $booking->room_id)->update(['status' => 'available']);
        }

        return response()->json([
            'message' => 'Booking berhasil diupdate',
            'data' => $booking->load(['user', 'room', 'roomType', 'payments'])
        ], 201);
    }


    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking berhasil dihapus']);
    }
}
