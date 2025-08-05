<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::withCount('cities')->paginate(10);
        return view('admin.provinces.index', compact('provinces'));
    }

    public function create()
    {
        return view('admin.provinces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Province::create([
            'name' => $request->name,
        ]);

        return redirect()->route('provinces.index')->with('success', 'Province berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $province = Province::findOrFail($id);
        return view('admin.provinces.edit', compact('province'));
    }

    public function update(Request $request, $id)
    {
        $province = Province::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $province->update(['name' => $request->name]);

        return redirect()->route('provinces.index')->with('success', 'Province berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $province = Province::findOrFail($id);
        $province->delete();

        return redirect()->route('provinces.index')->with('success', 'Province berhasil dihapus.');
    }
}
