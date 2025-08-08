<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingWebController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room', 'roomType', 'payments', 'floor', 'city', 'city.province'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'room', 'roomType', 'payments', 'city', 'floor', 'city.province'])->findOrFail($id);
        $firstPayment = $booking->payments->first();
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
        $booking = Booking::with('roomType', 'payments')->findOrFail($id);

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

        if ($request->action === 'refund') {
            $payment = $booking->payments->first();

            if (!$payment || $payment->payment_status !== 'refund_requested') {
                return back()->with('error', 'Refund request not found or already processed');
            }

            DB::beginTransaction();
            try {
                $payment->update([
                    'payment_status' => 'refunded',
                    'refund_amount' => $payment->amount,
                    'refunded_at' => now(),
                ]);

                $booking->update([
                    'status_booking' => 'canceled',
                    'refund_requested' => false
                ]);

                if ($booking->room) {
                    $booking->room->update(['status' => 'available']);
                }

                DB::commit();
                return back()->with('success', 'Refund berhasil diproses dan dana dikembalikan.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal memproses refund: ' . $e->getMessage());
            }
        }

        if ($request->action === 'reject_refund') {
            $payment = $booking->payments()->latest()->first();

            if ($payment && $payment->payment_status === 'refund_requested') {
                $payment->update([
                    'payment_status' => 'paid',
                    'request_refund_at' => null,
                    'request_refund_reason' => null,
                ]);
            }

            return redirect()->back()->with('success', 'Refund telah ditolak.');
        }

        if (
            $request->status_booking === 'canceled' &&
            $booking->status_booking === 'checkout_pending'
        ) {
            $booking->status_booking = 'checked_in';
            $booking->save();

            return redirect()->route('bookings.index')->with('success', 'Checkout telah direject. Status dikembalikan ke Check-in.');
        }


        $booking->update($dataUpdate);

        if ($request->status_booking === 'checked_out' && $booking->room_id) {
            Room::where('id', $booking->room_id)->update(['status' => 'available']);
        }

        if ($request->status_booking === 'canceled' && $booking->room_id) {
            Room::where('id', $booking->room_id)->update(['status' => 'available']);
        }

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function rejectRefund($id)
    {
        $booking = Booking::with('payments')->findOrFail($id);
        $payment = $booking->payments->first();

        if (!$payment || $payment->payment_status !== 'refund_requested') {
            return response()->json([
                'success' => false,
                'message' => 'Refund request not found'
            ], 422);
        }

        $payment->update([
            'payment_status' => 'paid',
            'request_refund_at' => null,
            'request_refund_reason' => null
        ]);

        $booking->update([
            'refund_requested' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Refund request rejected'
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
}
