<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelApiController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('city.province')->get();
        return response()->json($hotels);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_hotel' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'name_hotel',
            'city_id',
            'address',
            'description',
            'customer_service_phone'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create($data);

        return response()->json([
            'message' => 'Hotel created successfully',
            'data' => $hotel->load('city.province')
        ], 201);
    }

    public function show($id)
    {
        $hotel = Hotel::with('city.province')->findOrFail($id);
        return response()->json($hotel);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $request->validate([
            'name_hotel' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'name_hotel',
            'city_id',
            'address',
            'description',
            'customer_service_phone'
        ]);

        $hotel->update($data);

        return response()->json([
            'message' => 'Hotel updated successfully',
            'data' => $hotel->load('city.province')
        ]);
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);

        $hotel->delete();

        return response()->json([
            'message' => 'Hotel deleted successfully'
        ]);
    }
}
