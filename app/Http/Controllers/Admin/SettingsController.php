<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Services\ActivityLogService;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        if (!auth('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Hanya superadmin yang dapat mengubah pengaturan website.');
        }
        // Initialize defaults if not exists
        Settings::initializeDefaults();
        
        $settings = Settings::getAll();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Hanya superadmin yang dapat mengubah pengaturan website.');
        }
        $rules = [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg,webp|max:1024',
            'footer_text' => 'nullable|string',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            // Warna blok
            'blok_warna_merah' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'blok_warna_kuning' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'blok_warna_hijau' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'blok_warna_putih' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            // Threshold
            'blok_threshold_merah' => 'required|integer|min:0',
            'blok_threshold_kuning' => 'required|integer|min:0',
            'map_embed_url' => 'nullable|string|max:2000',
            'layanan_kelurahan' => 'nullable|string|max:5000',
        ];
        $messages = [
            'site_logo.image' => 'Logo harus berupa file gambar (JPEG, PNG, atau WebP).',
            'site_logo.mimes' => 'Format logo tidak diizinkan. Hanya JPEG, PNG, atau WebP.',
            'site_logo.max' => 'Ukuran logo maksimal 1 MB.',
            'site_logo.uploaded' => 'Upload logo gagal. Pastikan file valid dan ukurannya tidak melebihi 1 MB.',
        ];
        $request->validate($rules, $messages);

        // Update text settings
        Settings::set('site_name', $request->site_name);
        Settings::set('site_description', $request->site_description);
        Settings::set('footer_text', $request->footer_text);
        Settings::set('meta_description', $request->meta_description);
        Settings::set('meta_keywords', $request->meta_keywords);

        // Update warna blok
        Settings::set('blok_warna_merah', $request->blok_warna_merah);
        Settings::set('blok_warna_kuning', $request->blok_warna_kuning);
        Settings::set('blok_warna_hijau', $request->blok_warna_hijau);
        Settings::set('blok_warna_putih', $request->blok_warna_putih);
        
        // Update threshold (ensure integer and convert to string to match database storage)
        $thresholdMerah = (int) $request->blok_threshold_merah;
        $thresholdKuning = (int) $request->blok_threshold_kuning;
        Settings::set('blok_threshold_merah', (string) $thresholdMerah);
        Settings::set('blok_threshold_kuning', (string) $thresholdKuning);

        Settings::set('map_embed_url', $request->input('map_embed_url', ''));
        Settings::set('layanan_kelurahan', $request->input('layanan_kelurahan', ''));

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return back()->withErrors(['site_logo' => 'Format file tidak diizinkan. Hanya JPEG, PNG, atau WebP.'])->withInput();
            }
            if ($file->getSize() > 1 * 1024 * 1024) {
                return back()->withErrors(['site_logo' => 'Ukuran logo maksimal 1 MB.'])->withInput();
            }
            // Delete old logo if exists
            $oldLogo = Settings::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            // Compress dan simpan image (SVG akan disimpan langsung tanpa compression)
            $compressionService = new ImageCompressionService();
            // Simpan logo sebagai WEBP dengan target ukuran ~1 MB
            $logoPath = $compressionService->compressAndStore($file, 'logos', 'public', 90, 800, 800, 1 * 1024 * 1024);
            Settings::set('site_logo', $logoPath);
        }

        // Handle logo deletion
        if ($request->has('delete_logo') && $request->delete_logo == '1') {
            $oldLogo = Settings::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Settings::set('site_logo', null);
        }

        ActivityLogService::log('settings_update', null, ['keys' => array_keys($request->only(['site_name', 'site_description', 'footer_text', 'meta_description', 'meta_keywords', 'blok_warna_merah', 'blok_warna_kuning', 'blok_warna_hijau', 'blok_warna_putih', 'blok_threshold_merah', 'blok_threshold_kuning', 'map_embed_url', 'layanan_kelurahan']))]);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
