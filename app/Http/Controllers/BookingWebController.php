<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingWebController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room', 'roomType', 'payments', 'floor', 'city', 'city.province'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'room', 'roomType', 'payments', 'city', 'floor', 'floor', 'city.province'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::with(['roomType', 'room', 'city', 'floor'])->findOrFail($id);

        $availableRooms = Room::where('room_type_id', $booking->room_type_id)
            ->where('status', 'available')
            ->get();

        return view('admin.bookings.edit', compact('booking', 'availableRooms'));
    }


    public function update(Request $request, $id)
    {
        $booking = Booking::with('roomType')->findOrFail($id);

        $request->validate([
            'status_booking' => 'nullable|in:pending,paid,checked_in,checkout_pending,checked_out,canceled',
            'room_id' => 'nullable|exists:rooms,id',
            'checkin_at' => 'nullable|date',
            'checkout_at' => 'nullable|date|after:checkin_at',
        ]);

        $oldRoomId = $booking->room_id;

        if ($request->filled('room_id')) {
            $room = Room::findOrFail($request->room_id);

            if ($room->room_type_id !== $booking->room_type_id) {
                return back()->withErrors('Room tidak sesuai dengan tipe booking ini.');
            }

            if ($room->status !== 'available') {
                return back()->withErrors('Room tidak tersedia.');
            }

            if ($oldRoomId && $oldRoomId != $room->id) {
                Room::where('id', $oldRoomId)->update(['status' => 'available']);
            }

            $room->update(['status' => 'booked']);
        }

        $dataUpdate = $request->only(['status_booking', 'room_id', 'checkin_at', 'checkout_at']);

        if ($request->filled('checkin_at') && $request->filled('checkout_at')) {
            $checkin = Carbon::parse($request->checkin_at);
            $checkout = Carbon::parse($request->checkout_at);
            $nights = $checkin->diffInDays($checkout);

            $dataUpdate['nights'] = $nights;

            $pricePerNight = $booking->roomType->nightly_rate ?? $booking->price_per_night ?? 0;
            $dataUpdate['price_per_night'] = $pricePerNight;
            $dataUpdate['price_total'] = $nights * $pricePerNight;
        }

        $booking->update($dataUpdate);

        if ($request->status_booking === 'checked_out' && $booking->room_id) {
            Room::where('id', $booking->room_id)->update(['status' => 'available']);
        }

        if ($request->status_booking === 'canceled' && $booking->room_id) {
            Room::where('id', $booking->room_id)->update(['status' => 'available']);
        }

        if ($request->status_booking === 'canceled' && $booking->status_booking === 'checkout_pending') {
            $booking->update([
                'status_booking' => 'checked_in',
            ]);
            return redirect()->route('bookings.index')->with('success', 'Checkout telah direject. Status dikembalikan ke Check-in.');
        }


        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
