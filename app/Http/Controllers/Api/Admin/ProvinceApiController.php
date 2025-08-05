<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceApiController extends Controller
{
    public function index()
    {
        return response()->json(Province::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $province = Province::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Province created successfully',
            'data' => $province,
        ], 201);
    }

    public function show($id)
    {
        $province = Province::with('cities')->findOrFail($id);
        return response()->json($province);
    }

    public function update(Request $request, $id)
    {
        $province = Province::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $province->update(['name' => $request->name]);

        return response()->json([
            'message' => 'Province updated successfully',
            'data' => $province,
        ]);
    }

    public function destroy($id)
    {
        $province = Province::findOrFail($id);
        $province->delete();

        return response()->json([
            'message' => 'Province deleted successfully',
        ]);
    }

    public function cities($id)
    {
        $province = Province::with('cities')->findOrFail($id);
        return response()->json($province->cities);
    }
}
