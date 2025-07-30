<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeApiController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::all();

        return response()->json([
            'message' => 'Daftar semua room type.',
            'data' => $roomTypes
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name_type' => 'required|string',
            'facility' => 'nullable|string',
            'capacity' => 'required|integer',
            'nightly_rate' => 'required|integer',
            'photos' => 'nullable|array', 
            'photos.*' => 'string',       
        ]);

        $roomType = RoomType::create([
            'hotel_id' => $request->hotel_id,
            'name_type' => $request->name_type,
            'facility' => $request->facility,
            'capacity' => $request->capacity,
            'nightly_rate' => $request->nightly_rate,
            'photos' => $request->photos ? json_encode($request->photos) : null,
        ]);

        return response()->json([
            'message' => 'Room type berhasil dibuat.',
            'data' => $roomType
        ], 201);
    }

    public function show($id)
    {
        $roomType = RoomType::find($id);

        if (!$roomType) {
            return response()->json([
                'message' => 'Room type tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail room type.',
            'data' => $roomType
        ]);
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::find($id);

        if (!$roomType) {
            return response()->json([
                'message' => 'Room type tidak ditemukan.'
            ], 404);
        }

        $request->validate([
            'hotel_id' => 'sometimes|exists:hotels,id',
            'name_type' => 'sometimes|string',
            'facility' => 'nullable|string',
            'capacity' => 'sometimes|integer',
            'nightly_rate' => 'sometimes|integer',
            'photos' => 'nullable|array',
            'photos.*' => 'string',
        ]);

        $roomType->update([
            'hotel_id' => $request->hotel_id ?? $roomType->hotel_id,
            'name_type' => $request->name_type ?? $roomType->name_type,
            'facility' => $request->facility ?? $roomType->facility,
            'capacity' => $request->capacity ?? $roomType->capacity,
            'nightly_rate' => $request->nightly_rate ?? $roomType->nightly_rate,
            'photos' => $request->has('photos')
                ? json_encode($request->photos)
                : $roomType->photos,
        ]);

        return response()->json([
            'message' => 'Room type berhasil diperbarui.',
            'data' => $roomType
        ]);
    }

    public function destroy($id)
    {
        $roomType = RoomType::find($id);

        if (!$roomType) {
            return response()->json([
                'message' => 'Room type tidak ditemukan.'
            ], 404);
        }

        $roomType->delete();

        return response()->json([
            'message' => 'Room type berhasil dihapus.'
        ]);
    }
}
