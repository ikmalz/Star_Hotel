<?php

namespace App\Http\Controllers;

use App\Models\RoomFacility;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomFacilityController extends Controller
{
    /**
     * Tampilkan semua fasilitas kamar.
     */
    public function index()
    {
        $facilities = RoomFacility::with('roomType')->latest()->paginate(10);
        return view('room_facilities.list', compact('facilities'));
    }

    /**
     * Form tambah fasilitas kamar baru.
     */
    public function create()
    {
        $roomTypes = RoomType::all(); // ambil semua tipe kamar
        return view('room_facilities.create', compact('roomTypes'));
    }

    /**
     * Simpan fasilitas baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'facility_name' => 'required|string|max:255',
        ]);

        RoomFacility::create($request->all());

        return redirect()->route('room_facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Detail fasilitas kamar tertentu.
     */
    public function show(RoomFacility $roomFacility)
    {
        return view('room_facilities.show', compact('roomFacility'));
    }

    /**
     * Form edit fasilitas kamar.
     */
    public function edit(RoomFacility $roomFacility)
    {
        $roomTypes = RoomType::all();
        return view('room_facilities.edit', compact('roomFacility', 'roomTypes'));
    }

    /**
     * Update fasilitas kamar.
     */
    public function update(Request $request, RoomFacility $roomFacility)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'facility_name' => 'required|string|max:255',
        ]);

        $roomFacility->update($request->all());

        return redirect()->route('room_facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Hapus fasilitas kamar.
     */
    public function destroy(RoomFacility $roomFacility)
    {
        $roomFacility->delete();

        return redirect()->route('room_facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
