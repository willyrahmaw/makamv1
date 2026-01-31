<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlokMakam;
use Illuminate\Http\Request;

class BlokMakamController extends Controller
{
    public function index()
    {
        $bloks = BlokMakam::withCount('makam')->get();
        return view('admin.blok.index', compact('bloks'));
    }

    public function create()
    {
        return view('admin.blok.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_blok' => 'required|string|max:100|unique:blok_makam,nama_blok',
            'keterangan' => 'nullable|string|max:255',
        ]);

        BlokMakam::create($validated);

        return redirect()->route('admin.blok.index')->with('success', 'Blok makam berhasil ditambahkan.');
    }

    public function edit(BlokMakam $blok)
    {
        return view('admin.blok.edit', compact('blok'));
    }

    public function update(Request $request, BlokMakam $blok)
    {
        $validated = $request->validate([
            'nama_blok' => 'required|string|max:100|unique:blok_makam,nama_blok,' . $blok->id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $blok->update($validated);

        return redirect()->route('admin.blok.index')->with('success', 'Blok makam berhasil diperbarui.');
    }

    public function destroy(BlokMakam $blok)
    {
        if ($blok->makam()->count() > 0) {
            return redirect()->route('admin.blok.index')->with('error', 'Tidak dapat menghapus blok yang masih memiliki data makam.');
        }

        $blok->delete();

        return redirect()->route('admin.blok.index')->with('success', 'Blok makam berhasil dihapus.');
    }
}
