<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('province')->paginate(10);
        $provinces = Province::all();
        return view('cities.list', compact('cities', 'provinces'));
    }

    public function create()
    {
        $provinces = Province::all();
        return view('cities.list', compact('provinces')); 
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'province_id' => 'required|exists:provinces,id'
            ]);

            $city = City::create([
                'name' => $request->name,
                'province_id' => $request->province_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kota berhasil ditambahkan',
                'data' => $city
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kota: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit(City $city)
    {
        try {
            return response()->json([
                'id' => $city->id,
                'name' => $city->name,
                'province_id' => $city->province_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memuat data kota: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, City $city)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'province_id' => 'required|exists:provinces,id'
            ]);

            $city->update([
                'name' => $request->name,
                'province_id' => $request->province_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kota berhasil diperbarui',
                'data' => $city->fresh() 
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kota: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $city = City::findOrFail($id);
            $city->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kota berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kota: ' . $e->getMessage()
            ], 500);
        }
    }
}
