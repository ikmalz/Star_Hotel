<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index($roomTypeId)
    {
        $roomType = RoomType::with('hotel')->findOrFail($roomTypeId);
        $floors = Floor::where('room_type_id', $roomTypeId)->get();

        return view('floors.list', compact('roomType', 'floors'));
    }

    public function all()
    {
        $floors = Floor::with('roomType.hotel')->get(); 
        return view('floors.all', compact('floors'));
    }


    public function store(Request $request, $roomTypeId)
    {
        $request->validate([
            'floor_number' => 'required|integer|min:1',
        ]);

        Floor::create([
            'room_type_id' => $roomTypeId,
            'floor_number' => $request->floor_number,
        ]);

        return redirect()->route('floors.index', $roomTypeId)->with('success', 'Lantai berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $floor = Floor::findOrFail($id);
        $roomTypeId = $floor->room_type_id;
        $floor->delete();

        return redirect()->route('floors.index', $roomTypeId)->with('success', 'Lantai berhasil dihapus.');
    }
}
