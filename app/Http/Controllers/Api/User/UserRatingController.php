<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'value' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        $hasBooking = Booking::where('user_id', $user->id)
            ->where('room_type_id', $request->room_type_id)
            ->whereHas('roomType', function ($query) use ($request) {
                $query->where('hotel_id', $request->hotel_id);
            })
            ->where('status_booking', 'checked_out') 
            ->exists();


        if (!$hasBooking) {
            return response()->json([
                'message' => 'You cannot rate this hotel because you have never booked it.',
            ], 403);
        }

        $existing = Rating::where('user_id', $user->id)
            ->where('hotel_id', $request->hotel_id)
            ->where('room_type_id', $request->room_type_id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You have already rated this hotel and room type.',
                'data' => $existing
            ], 409);
        }

        $rating = Rating::create([
            'user_id' => $user->id,
            'hotel_id' => $request->hotel_id,
            'room_type_id' => $request->room_type_id,
            'value' => $request->value,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully.',
            'data' => $rating
        ], 201);
    }

    public function index()
    {
        $user = Auth::user();

        $ratings = Rating::with(['hotel', 'roomType'])->where('user_id', $user->id)->get();

        return response()->json([
            'data' => $ratings
        ]);
    }

    public function show($id)
    {
        $rating = Rating::with(['hotel', 'roomType'])->findOrFail($id);

        return response()->json([
            'data' => $rating
        ]);
    }
}
