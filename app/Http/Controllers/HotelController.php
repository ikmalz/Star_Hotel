<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

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
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        try {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('hotels', 'public');
            }

            $hotel = Hotel::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Hotel berhasil ditambahkan.',
                'data' => $hotel,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan hotel: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name_hotel' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        try {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('hotels', 'public');
            }

            $hotel->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Hotel berhasil diperbarui.',
                'data' => $hotel,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui hotel: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function destroy(Request $request, Hotel $hotel)
    {
        $hotel->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Hotel berhasil dihapus.']);
        }

        return redirect()->route('hotels.index')->with('success', 'Hotel berhasil dihapus.');
    }
}
    