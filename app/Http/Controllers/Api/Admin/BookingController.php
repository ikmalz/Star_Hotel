<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with(['user', 'room', 'roomType'])->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_type_id' => 'required|exists:room_types,id',
            'checkin_at' => 'required|date',
            'checkout_at' => 'required|date|after:checkin_at',
            'price_total' => 'required|integer',
        ]);

        $code = 'BK-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'user_id' => $request->user_id,
            'room_type_id' => $request->room_type_id,
            'room_id' => null,
            'checkin_at' => $request->checkin_at,
            'checkout_at' => $request->checkout_at,
            'status_booking' => 'pending',
            'code_booking' => $code,
            'price_total' => $request->price_total,
        ]);

        return response()->json([
            'message' => 'Booking berhasil dibuat',
            'data' => $booking
        ], 201);
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'room', 'roomType'])->findOrFail($id);
        return response()->json($booking);
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
        }

        $booking->update($request->only([
            'status_booking',
            'room_id',
            'checkin_at',
            'checkout_at'
        ]));

        return response()->json([
            'message' => 'Booking berhasil diupdate',
            'data' => $booking->load(['user', 'room'])
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking berhasil dihapus']);
    }
}
