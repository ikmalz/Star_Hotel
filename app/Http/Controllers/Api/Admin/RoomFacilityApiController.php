<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomFacility;
use Illuminate\Http\Request;

class RoomFacilityApiController extends Controller
{
    // Tidak ada middleware auth di constructor, jadi tidak pakai token

    public function index()
    {
        $facilities = RoomFacility::with('roomType')->latest()->get();
        return response()->json($facilities);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'facility_name' => 'required|string|max:255',
        ]);

        $facility = RoomFacility::create([
            'room_type_id' => $request->room_type_id,
            'facility_name' => $request->facility_name,
        ]);

        return response()->json([
            'message' => 'Fasilitas berhasil ditambahkan.',
            'data' => $facility
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $facility = RoomFacility::findOrFail($id);

        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'facility_name' => 'required|string|max:255',
        ]);

        $facility->update([
            'room_type_id' => $request->room_type_id,
            'facility_name' => $request->facility_name,
        ]);

        return response()->json([
            'message' => 'Fasilitas berhasil diperbarui.',
            'data' => $facility
        ]);
    }

    public function destroy($id)
    {
        $facility = RoomFacility::findOrFail($id);
        $facility->delete();

        return response()->json([
            'message' => 'Fasilitas berhasil dihapus.'
        ]);
    }
}
