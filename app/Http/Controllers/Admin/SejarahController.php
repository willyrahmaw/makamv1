<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sejarah;
use Illuminate\Http\Request;

class SejarahController extends Controller
{
    public function index()
    {
        $sejarah = Sejarah::orderBy('created_at', 'desc')->get();
        return view('admin.sejarah.index', compact('sejarah'));
    }

    public function create()
    {
        return view('admin.sejarah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'konten' => 'required|string',
            'narasumber_nama' => 'nullable|string|max:255',
            'narasumber_lahir' => 'nullable|string|max:255',
            'narasumber_jabatan' => 'nullable|string|max:255',
            'aktif' => 'boolean',
        ]);

        // Jika ada yang diaktifkan, nonaktifkan yang lain
        if ($request->has('aktif') && $request->aktif) {
            Sejarah::where('aktif', true)->update(['aktif' => false]);
        }

        Sejarah::create($validated);

        return redirect()->route('admin.sejarah.index')->with('success', 'Sejarah berhasil ditambahkan.');
    }

    public function edit(Sejarah $sejarah)
    {
        return view('admin.sejarah.edit', compact('sejarah'));
    }

    public function update(Request $request, Sejarah $sejarah)
    {
        $validated = $request->validate([
            'konten' => 'required|string',
            'narasumber_nama' => 'nullable|string|max:255',
            'narasumber_lahir' => 'nullable|string|max:255',
            'narasumber_jabatan' => 'nullable|string|max:255',
            'aktif' => 'boolean',
        ]);

        // Jika diaktifkan, nonaktifkan yang lain
        if ($request->has('aktif') && $request->aktif) {
            Sejarah::where('aktif', true)->where('id', '!=', $sejarah->id)->update(['aktif' => false]);
        }

        $sejarah->update($validated);

        return redirect()->route('admin.sejarah.index')->with('success', 'Sejarah berhasil diperbarui.');
    }

    public function destroy(Sejarah $sejarah)
    {
        $sejarah->delete();

        return redirect()->route('admin.sejarah.index')->with('success', 'Sejarah berhasil dihapus.');
    }
}
