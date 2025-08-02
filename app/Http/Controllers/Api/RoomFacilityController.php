<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomFacility;
use Illuminate\Http\Request;

class RoomFacilityController extends Controller
{
    public function index()
    {
        $facilities = RoomFacility::with('roomType')->get();
        return response()->json($facilities);
    }

    public function show($roomTypeId)
    {
        $facilities = RoomFacility::where('room_type_id', $roomTypeId)->get();

        if ($facilities->isEmpty()) {
            return response()->json(['message' => 'Fasilitas tidak ditemukan'], 404);
        }

        return response()->json($facilities);
    }
}
