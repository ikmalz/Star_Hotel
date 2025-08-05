<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::latest()->paginate(10);
        $cities = City::all();

        return view('hotels.list', compact('hotels', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_hotel' => 'required|string|max:255',
            'address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel berhasil ditambahkan',
            'data' => $hotel
        ]);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validated = $request->validate([
            'name_hotel' => 'required|string|max:255',
            'address' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel berhasil diperbarui',
            'data' => $hotel
        ]);
    }

    public function destroy(Request $request, Hotel $hotel)
    {
        if ($hotel->image && Storage::disk('public')->exists($hotel->image)) {
            Storage::disk('public')->delete($hotel->image);
        }

        $hotel->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Hotel berhasil dihapus.']);
        }

        return redirect()->route('hotels.index')->with('success', 'Hotel berhasil dihapus.');
    }
}
