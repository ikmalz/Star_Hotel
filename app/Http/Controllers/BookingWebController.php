<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingWebController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room', 'roomType', 'payments'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'room', 'roomType', 'payments'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::with(['roomType', 'room'])->findOrFail($id);
        $availableRooms = Room::where('room_type_id', $booking->room_type_id)
                              ->where('status', 'available')
                              ->get();
        return view('admin.bookings.edit', compact('booking', 'availableRooms'));
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
                return redirect()->back()->withErrors('Room tidak sesuai dengan type booking ini.');
            }

            if ($room->status !== 'available') {
                return redirect()->back()->withErrors('Room tidak tersedia.');
            }

            $room->update(['status' => 'booked']);
        }

        $booking->update($request->only([
            'status_booking',
            'room_id',
            'checkin_at',
            'checkout_at'
        ]));

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
