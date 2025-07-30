<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $roomTypes = RoomType::where('hotel_id', $hotelId)->get();

        return view('roomType.list', compact('hotel', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name_type' => 'required|string',
            'facility' => 'nullable|string',
            'capacity' => 'required|integer',
            'nightly_rate' => 'required|integer',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('room_photos', 'public'); 
                $photoPaths[] = $path;
            }
        }

        RoomType::create([
            'hotel_id' => $request->hotel_id,
            'name_type' => $request->name_type,
            'facility' => $request->facility,
            'capacity' => $request->capacity,
            'nightly_rate' => $request->nightly_rate,
            'photos' => $photoPaths, 
        ]);

        return redirect()->back()->with('success', 'Room type berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        $request->validate([
            'name_type' => 'required|string',
            'facility' => 'nullable|string',
            'capacity' => 'required|integer',
            'nightly_rate' => 'required|integer',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPaths = $roomType->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('room_photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $roomType->update([
            'name_type' => $request->name_type,
            'facility' => $request->facility,
            'capacity' => $request->capacity,
            'nightly_rate' => $request->nightly_rate,
            'photos' => $photoPaths,
        ]);

        return redirect()->back()->with('success', 'Room type berhasil diperbarui');
    }

    public function destroy($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomType->delete();

        return redirect()->back()->with('success', 'Room type berhasil dihapus');
    }
}
