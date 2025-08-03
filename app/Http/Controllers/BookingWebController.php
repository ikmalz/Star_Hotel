<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingWebController extends Controller
{
    public function index()
    {
       $bookings = Booking::with(['user', 'room', 'roomType'])->get();
        return view('admin.bookings.index', compact('bookings'));

    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status_booking' => 'required|in:pending,paid'
        ]);

        $booking->update([
            'status_booking' => $request->status_booking
        ]);

        return redirect()->route('bookings.index')->with('success', 'Status booking berhasil diperbarui.');
    }
}

