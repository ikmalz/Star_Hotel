<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['roomType', 'payments'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return BookingResource::collection($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'floor_id' => 'required|exists:floors,id',
            'room_type_id' => 'required|exists:room_types,id',
            'checkin_at' => 'required|date',
            'checkout_at' => 'required|date|after:checkin_at',
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

        $roomType = RoomType::findOrFail($request->room_type_id);
        $pricePerNight = $roomType->nightly_rate ?? $roomType->price_per_night ?? 0;

        if ($pricePerNight <= 0) {
            return response()->json(['message' => 'Harga kamar belum diatur.'], 422);
        }

        $checkin = Carbon::parse($request->checkin_at);
        $checkout = Carbon::parse($request->checkout_at);
        $nights = $checkin->diffInDays($checkout);

        if ($nights <= 0) {
            return response()->json(['message' => 'Durasi menginap minimal 1 malam.'], 422);
        }

        $priceTotal = $nights * $pricePerNight;
        $code = 'BK-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'user_id' => $userId,
            'city_id' => $request->city_id,
            'floor_id' => $request->floor_id,
            'room_type_id' => $request->room_type_id,
            'room_id' => null,
            'checkin_at' => $request->checkin_at,
            'checkout_at' => $request->checkout_at,
            'nights' => $nights,
            'price_per_night' => $pricePerNight,
            'price_total' => $priceTotal,
            'status_booking' => 'pending',
            'code_booking' => $code,
        ]);



        return (new BookingResource($booking->load(['roomType', 'city', 'floor'])))
            ->additional(['message' => 'Booking berhasil dibuat, silakan lakukan pembayaran.']);
    }



    public function show(Request $request, $id)
    {
        $booking = Booking::with(['roomType', 'payments'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return new BookingResource($booking);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'status_booking' => 'required|in:canceled',
        ]);

        if ($booking->status_booking !== 'pending') {
            return response()->json([
                'message' => 'Hanya booking dengan status pending yang bisa dibatalkan.'
            ], 422);
        }

        $booking->update(['status_booking' => 'canceled']);

        return response()->json([
            'message' => 'Booking berhasil dibatalkan',
            'data' => new BookingResource($booking)
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->where('status_booking', 'pending')
            ->findOrFail($id);

        $booking->delete();

        return response()->json(['message' => 'Booking berhasil dihapus']);
    }

    public function checkout(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->where('status_booking', 'checked_in')
            ->findOrFail($id);

        $booking->update(['status_booking' => 'checkout_pending']);

        return response()->json([
            'message' => 'Permintaan checkout berhasil dikirim, menunggu persetujuan admin.',
            'data' => $booking->load('room')
        ]);
    }
}
