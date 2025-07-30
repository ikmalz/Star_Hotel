<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::latest()->paginate(10);
        return view('hotels.list', compact('hotels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_hotel' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel berhasil ditambahkan.',
            'data' => $hotel,
        ], 201);
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name_hotel' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('image')) {
            if ($hotel->image && Storage::disk('public')->exists($hotel->image)) {
                Storage::disk('public')->delete($hotel->image);
            }
            $validated['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel berhasil diperbarui.',
            'data' => $hotel,
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
