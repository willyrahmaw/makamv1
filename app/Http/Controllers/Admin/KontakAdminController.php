<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakAdmin;
use Illuminate\Http\Request;

class KontakAdminController extends Controller
{
    /**
     * Show the form for editing the contact.
     */
    public function edit()
    {
        $kontak = KontakAdmin::getKontak();
        return view('admin.kontak.edit', compact('kontak'));
    }

    /**
     * Update the contact in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'telepon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'jam_layanan' => 'nullable|string',
        ]);

        $kontak = KontakAdmin::getKontak();
        $kontak->update($request->only(['telepon', 'email', 'alamat', 'jam_layanan']));

        return redirect()->route('admin.kontak.edit')
            ->with('success', 'Kontak admin berhasil diperbarui.');
    }
}
