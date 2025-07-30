<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelApiController extends Controller
{
    public function index()
    {
        return response()->json(Hotel::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_hotel' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'name_hotel',
            'address',
            'city',
            'province',
            'description',
            'customer_service_phone'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create($data);

        return response()->json([
            'message' => 'Hotel created successfully',
            'data' => $hotel
        ], 201);
    }


    public function show($id)
    {
        $hotel = Hotel::findOrFail($id);
        return response()->json($hotel);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $request->validate([
            'name_hotel' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'customer_service_phone' => 'nullable|string|max:20',
        ]);

        $hotel->update($request->all());

        return response()->json([
            'message' => 'Hotel updated successfully',
            'data' => $hotel
        ]);
    }

    public function destroy($id)
    {
        Hotel::destroy($id);

        return response()->json([
            'message' => 'Hotel deleted successfully'
        ]);
    }
}
