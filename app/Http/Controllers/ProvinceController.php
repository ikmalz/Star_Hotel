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
        return view('provinces.list', compact('provinces'));
    }

    public function create()
    {
        return view('provinces.create');
    }

  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $province = Province::create([
        'name' => $request->name,
    ]);

    // Kalau request via AJAX, balikin JSON
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Province berhasil ditambahkan.',
            'data' => $province
        ]);
    }

    return redirect()->route('provinces.index')
        ->with('success', 'Province berhasil ditambahkan.');
}

public function update(Request $request, $id)
{
    $province = Province::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $province->update(['name' => $request->name]);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Province berhasil diperbarui.',
            'data' => $province
        ]);
    }

    return redirect()->route('provinces.index')
        ->with('success', 'Province berhasil diperbarui.');
}

public function edit($id)
{
    $province = Province::findOrFail($id);

    // Kalau request dari fetch(), balikin JSON
    if (request()->expectsJson()) {
        return response()->json($province);
    }

    // Kalau request biasa, balikin view edit (opsional)
    return view('provinces.edit', compact('province'));
}


    public function destroy($id)
    {
        $province = Province::findOrFail($id);
        $province->delete();

        return redirect()->route('provinces.index')->with('success', 'Province berhasil dihapus.');
    }
}
