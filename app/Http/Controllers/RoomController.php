<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomPhoto;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index($roomTypeId)
    {
        $roomType = RoomType::findOrFail($roomTypeId);
        $hotel = $roomType->hotel;
        $rooms = Room::with(['roomType', 'photos'])->where('room_type_id', $roomTypeId)->get();

        return view('rooms.list', compact('rooms', 'roomType', 'hotel'));
    }

    public function show($id)
    {
        $room = Room::with(['roomType.hotel', 'photos'])->findOrFail($id);
        return view('rooms.show', compact('room'));
    }

    public function store(Request $request, $roomTypeId)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|max:255',
            'floor' => 'nullable|integer',
            'status' => 'required|in:available,booked,occupied,maintenance',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $room = Room::create([
            'room_type_id' => $roomTypeId,
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('room_photos', 'public');
                RoomPhoto::create([
                    'room_id' => $room->id,
                    'photo' => $path
                ]);
            }
        }

        return redirect()->route('rooms.index', $roomTypeId)->with('success', 'Room berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'floor' => 'nullable|integer',
            'status' => 'in:available,booked,occupied,maintenance',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $room->update($request->only(['room_number', 'floor', 'status', 'notes']));

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('room_photos', 'public');
                RoomPhoto::create([
                    'room_id' => $room->id,
                    'photo' => $path
                ]);
            }
        }



        return redirect()->route('rooms.index', $room->room_type_id)
            ->with('success', 'Room berhasil diupdate.');
    }


    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $roomTypeId = $room->room_type_id;
        $room->delete();

        return redirect()->route('rooms.index', $roomTypeId)
            ->with('success', 'Room berhasil dihapus.');
    }
}
