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
                'location' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'customer_service_phone' => 'nullable|string|max:15',
            ]);

            $hotel = Hotel::create($request->all());
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
                'location' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'customer_service_phone' => 'nullable|string|max:15',
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
            return response()->json(['message' => 'Hotel deleted successfully']);
        }
    }
