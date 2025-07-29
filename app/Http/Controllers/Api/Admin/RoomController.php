<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->get();
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|unique:rooms,room_number',
            'status' => 'in:available,booked,maintenance'
        ]);

        $room = Room::create([
            'room_type_id' => $request->room_type_id,
            'room_number' => $request->room_number,
            'status' => $request->status ?? 'available'
        ]);

        return response()->json([
            'message' => 'Room berhasil dibuat.',
            'data' => $room
        ], 201);
    }

    public function show($id)
    {
        $room = Room::with('roomType')->find($id);

        if (!$room) {
            return response()->json(['message' => 'Room tidak ditemukan.'], 404);
        }

        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['message' => 'Room tidak ditemukan.'], 404);
        }

        $request->validate([
            'room_type_id' => 'sometimes|exists:room_types,id',
            'room_number' => 'sometimes|string|unique:rooms,room_number,' . $room->id,
            'status' => 'sometimes|in:available,booked,maintenance'
        ]);

        $room->update($request->only(['room_type_id', 'room_number', 'status']));

        return response()->json([
            'message' => 'Room berhasil diperbarui.',
            'data' => $room
        ]);
    }

    public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['message' => 'Room tidak ditemukan.'], 404);
        }

        $room->delete();

        return response()->json(['message' => 'Room berhasil dihapus.']);
    }
}
