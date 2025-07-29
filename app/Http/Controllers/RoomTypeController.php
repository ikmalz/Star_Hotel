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
        ]);

        RoomType::create($request->all());

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
        ]);

        $roomType->update($request->all());

        return redirect()->back()->with('success', 'Room type berhasil diperbarui');
    }

    public function destroy($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomType->delete();

        return redirect()->back()->with('success', 'Room type berhasil dihapus');
    }
}
