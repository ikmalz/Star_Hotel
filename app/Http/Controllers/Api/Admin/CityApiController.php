<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityApiController extends Controller
{
    public function index()
    {
        return response()->json(City::with('province')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
        ]);

        $city = City::create([
            'province_id' => $request->province_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'City created successfully',
            'data' => $city,
        ], 201);
    }

    public function show($id)
    {
        $city = City::with(['province', 'hotels'])->findOrFail($id);
        return response()->json($city);
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
        ]);

        $city->update([
            'province_id' => $request->province_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'City updated successfully',
            'data' => $city,
        ]);
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json([
            'message' => 'City deleted successfully',
        ]);
    }

    public function hotels($id)
    {
        $city = City::with('hotels')->findOrFail($id);
        return response()->json($city->hotels);
    }
}
