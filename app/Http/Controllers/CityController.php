<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('province')->paginate(10);
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        $provinces = Province::all();
        return view('admin.cities.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
        ]);

        City::create([
            'province_id' => $request->province_id,
            'name' => $request->name,
        ]);

        return redirect()->route('cities.index')->with('success', 'City berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        $provinces = Province::all();
        return view('admin.cities.edit', compact('city', 'provinces'));
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

        return redirect()->route('cities.index')->with('success', 'City berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('cities.index')->with('success', 'City berhasil dihapus.');
    }
}