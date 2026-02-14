<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KontakAdmin;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class KontakAdminController extends Controller
{
    /**
     * Show the form for editing the contact.
     */
    public function edit()
    {
        if (!auth('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Hanya superadmin yang dapat mengubah kontak admin.');
        }
        $kontak = KontakAdmin::getKontak();
        return view('admin.kontak.edit', compact('kontak'));
    }

    /**
     * Update the contact in storage.
     */
    public function update(Request $request)
    {
        if (!auth('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Hanya superadmin yang dapat mengubah kontak admin.');
        }
        $request->validate([
            'telepon' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'jam_layanan' => 'nullable|string',
        ]);

        $kontak = KontakAdmin::getKontak();
        $kontak->update($request->only(['telepon', 'email', 'alamat', 'jam_layanan']));
        ActivityLogService::log('kontak_update', $kontak);

        return redirect()->route('admin.kontak.edit')
            ->with('success', 'Kontak admin berhasil diperbarui.');
    }
}
