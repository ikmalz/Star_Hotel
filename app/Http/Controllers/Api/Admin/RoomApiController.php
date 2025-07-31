<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomApiController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['roomType.hotel', 'photos'])->get();
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|unique:rooms,room_number',
            'floor' => 'nullable|integer',
            'status' => 'in:available,booked,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        $room = Room::create($request->all());
        return response()->json($room, 201);
    }

    public function show($id)
    {
        $room = Room::with(['roomType.hotel', 'photos'])->findOrFail($id);
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_type_id' => 'exists:room_types,id',
            'room_number' => 'string|unique:rooms,room_number,' . $room->id,
            'floor' => 'nullable|integer',
            'status' => 'in:available,booked,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        $room->update($request->all());
        return response()->json($room);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(['message' => 'Room deleted successfully.']);
    }

    public function byHotel($hotelId)
    {
        $rooms = Room::whereHas('roomType', function ($query) use ($hotelId) {
            $query->where('hotel_id', $hotelId);
        })->with('roomType')->get();

        return response()->json($rooms);
    }
}