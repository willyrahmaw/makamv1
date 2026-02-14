<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlokMakam;
use App\Services\ActivityLogService;
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

        $created = BlokMakam::create($validated);
        ActivityLogService::log('blok_create', $created);

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
        ActivityLogService::log('blok_update', $blok);

        return redirect()->route('admin.blok.index')->with('success', 'Blok makam berhasil diperbarui.');
    }

    public function destroy(BlokMakam $blok)
    {
        if (!auth('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.blok.index')->with('error', 'Admin tidak diizinkan menghapus data.');
        }

        if ($blok->makam()->count() > 0) {
            return redirect()->route('admin.blok.index')->with('error', 'Tidak dapat menghapus blok yang masih memiliki data makam.');
        }

        ActivityLogService::log('blok_delete', $blok);
        $blok->delete();

        return redirect()->route('admin.blok.index')->with('success', 'Blok makam berhasil dihapus.');
    }
}
