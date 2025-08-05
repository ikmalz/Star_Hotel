<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;

class FloorApiController extends Controller
{
    public function index()
    {
        $floors = Floor::with(['roomType.hotel'])->get();

        return response()->json([
            'message' => 'Daftar semua lantai.',
            'data' => $floors
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'floor_number' => 'required|integer|min:1',
        ]);

        $floor = Floor::create([
            'room_type_id' => $request->room_type_id,
            'floor_number' => $request->floor_number,
        ]);

        $floor->load('roomType.hotel');

        return response()->json([
            'message' => 'Lantai berhasil dibuat.',
            'data' => $floor
        ], 201); 
    }

    public function show($id)
    {
        $floor = Floor::with('roomType.hotel')->find($id);

        if (!$floor) {
            return response()->json([
                'message' => 'Lantai tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail lantai.',
            'data' => $floor
        ]);
    }


    public function update(Request $request, $id)
    {
        $floor = Floor::find($id);

        if (!$floor) {
            return response()->json([
                'message' => 'Lantai tidak ditemukan.'
            ], 404);
        }

        $request->validate([
            'room_type_id' => 'sometimes|exists:room_types,id',
            'floor_number' => 'sometimes|integer|min:1',
        ]);

        $floor->update($request->only(['room_type_id', 'floor_number']));

        return response()->json([
            'message' => 'Lantai berhasil diperbarui.',
            'data' => $floor
        ]);
    }

    public function destroy($id)
    {
        $floor = Floor::find($id);

        if (!$floor) {
            return response()->json([
                'message' => 'Lantai tidak ditemukan.'
            ], 404);
        }

        $floor->delete();

        return response()->json([
            'message' => 'Lantai berhasil dihapus.'
        ]);
    }

    public function getFloorsByRoomType($roomTypeId)
    {
        $floors = Floor::where('room_type_id', $roomTypeId)
            ->with(['rooms' => function ($q) {
                $q->where('status', 'available');
            }])
            ->get();

        return response()->json([
            'message' => 'Daftar lantai untuk Room Type terpilih.',
            'data' => $floors
        ]);
    }

    public function getProvinces()
    {
        $provinces = Hotel::select('province')->distinct()->pluck('province');

        return response()->json([
            'message' => 'Daftar provinsi.',
            'data' => $provinces
        ]);
    }

    public function getCitiesByProvince($province)
    {
        $cities = Hotel::where('province', $province)
            ->select('city')->distinct()
            ->pluck('city');

        return response()->json([
            'message' => 'Daftar kota di provinsi ' . $province,
            'data' => $cities
        ]);
    }

    public function getHotelsByCity($city)
    {
        $hotels = Hotel::where('city', $city)
            ->select('id', 'name_hotel', 'address', 'city', 'province')
            ->get();

        return response()->json([
            'message' => 'Daftar hotel di kota ' . $city,
            'data' => $hotels
        ]);
    }
}
