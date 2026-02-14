<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Makam;
use App\Models\BlokMakam;
use App\Services\ImageCompressionService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MakamController extends Controller
{
    public function index(Request $request)
    {
        $query = Makam::with('blok');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_ayah', 'like', "%{$search}%")
                  ->orWhere('ahli_waris', 'like', "%{$search}%");
            });
        }

        if ($request->filled('blok_id')) {
            $query->where('blok_id', $request->blok_id);
        }

        $makam = $query->orderBy('id', 'desc')->paginate(15);
        $bloks = BlokMakam::all();

        return view('admin.makam.index', compact('makam', 'bloks'));
    }

    public function create()
    {
        $bloks = BlokMakam::all();
        return view('admin.makam.create', compact('bloks'));
    }

    /**
     * Normalisasi input tanggal: terima dd/mm/yyyy atau dd-mm-yyyy, kembalikan Y-m-d.
     */
    private static function normalizeDateInput(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $value = trim($value);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $m)) {
            $d = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $mo = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            return $m[3] . '-' . $mo . '-' . $d;
        }
        return $value;
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal_lahir' => self::normalizeDateInput($request->input('tanggal_lahir')),
            'tanggal_wafat' => self::normalizeDateInput($request->input('tanggal_wafat')),
        ]);
        $rules = [
            'dikenali' => 'nullable|boolean',
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan,tidak-diketahui',
            'nama_ayah' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_wafat' => 'nullable|date',
            'blok_id' => 'nullable|exists:blok_makam,id',
            'nomor_makam' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'ahli_waris' => 'nullable|string|max:255',
            'telepon_ahli_waris' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:15360',
        ];
        $messages = [
            'foto.image' => 'Foto makam harus berupa file gambar (JPEG, PNG, atau WebP).',
            'foto.mimes' => 'Format foto tidak diizinkan. Hanya JPEG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 15 MB.',
            'foto.uploaded' => 'Upload foto gagal. Pastikan file valid dan ukurannya tidak melebihi 15 MB.',
        ];
        $validated = $request->validate($rules, $messages);
        $validated['dikenali'] = (bool) $request->input('dikenali', true);

        // Semua field nullable: simpan apa adanya (null jika kosong)
        $validated['nama_lengkap'] = trim($validated['nama_lengkap'] ?? '') ?: null;
        $validated['jenis_kelamin'] = $validated['jenis_kelamin'] ?? null;
        $validated['tanggal_wafat'] = $validated['tanggal_wafat'] ?? null;
        $validated['blok_id'] = $validated['blok_id'] ?? null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return back()->withErrors(['foto' => 'Format file tidak diizinkan. Hanya JPEG, PNG, GIF, atau WebP.'])->withInput();
            }
            if ($file->getSize() > 15 * 1024 * 1024) {
                return back()->withErrors(['foto' => 'Ukuran foto maksimal 15 MB.'])->withInput();
            }
            // Simpan image ukuran asli (hanya kompresi kualitas, tanpa resize)
            $compressionService = new ImageCompressionService();
            $validated['foto'] = $compressionService->compressAndStore($file, 'makam', 'public', 85, 99999, 99999);
            
            // Generate cover image otomatis (800x600 dengan crop center)
            $coverPath = $compressionService->generateCover($file, 'makam', 'public', 800, 600, 85);
            if ($coverPath) {
                $validated['cover'] = $coverPath;
            }
        }

        $created = Makam::create($validated);
        ActivityLogService::log('makam_create', $created);

        return redirect()->route('admin.makam.index')->with('success', 'Data makam berhasil ditambahkan.');
    }

    public function show(Makam $makam)
    {
        $makam->load('blok');
        return view('admin.makam.show', compact('makam'));
    }

    public function edit(Makam $makam)
    {
        $bloks = BlokMakam::all();
        return view('admin.makam.edit', compact('makam', 'bloks'));
    }

    public function update(Request $request, Makam $makam)
    {
        $request->merge([
            'tanggal_lahir' => self::normalizeDateInput($request->input('tanggal_lahir')),
            'tanggal_wafat' => self::normalizeDateInput($request->input('tanggal_wafat')),
        ]);
        $rules = [
            'dikenali' => 'nullable|boolean',
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan,tidak-diketahui',
            'nama_ayah' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_wafat' => 'nullable|date',
            'blok_id' => 'nullable|exists:blok_makam,id',
            'nomor_makam' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
            'ahli_waris' => 'nullable|string|max:255',
            'telepon_ahli_waris' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:15360',
        ];
        $messages = [
            'foto.image' => 'Foto makam harus berupa file gambar (JPEG, PNG, GIF, atau WebP).',
            'foto.mimes' => 'Format foto tidak diizinkan. Hanya JPEG, PNG, GIF, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 15 MB.',
            'foto.uploaded' => 'Upload foto gagal. Pastikan file valid dan ukurannya tidak melebihi 15 MB.',
        ];
        $validated = $request->validate($rules, $messages);
        $validated['dikenali'] = (bool) $request->input('dikenali', true);

        // Semua field nullable: simpan apa adanya (null jika kosong)
        $validated['nama_lengkap'] = trim($validated['nama_lengkap'] ?? '') ?: null;
        $validated['jenis_kelamin'] = $validated['jenis_kelamin'] ?? null;
        $validated['tanggal_wafat'] = $validated['tanggal_wafat'] ?? null;
        $validated['blok_id'] = $validated['blok_id'] ?? null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return back()->withErrors(['foto' => 'Format file tidak diizinkan. Hanya JPEG, PNG, GIF, atau WebP.'])->withInput();
            }
            if ($file->getSize() > 15 * 1024 * 1024) {
                return back()->withErrors(['foto' => 'Ukuran foto maksimal 15 MB.'])->withInput();
            }
            if ($makam->foto) {
                Storage::disk('public')->delete($makam->foto);
            }
            // Hapus cover lama jika ada
            if ($makam->cover) {
                Storage::disk('public')->delete($makam->cover);
            }
            // Simpan image ukuran asli (hanya kompresi kualitas, tanpa resize)
            $compressionService = new ImageCompressionService();
            $validated['foto'] = $compressionService->compressAndStore($file, 'makam', 'public', 85, 99999, 99999);
            
            // Generate cover image otomatis (800x600 dengan crop center)
            $coverPath = $compressionService->generateCover($file, 'makam', 'public', 800, 600, 85);
            if ($coverPath) {
                $validated['cover'] = $coverPath;
            }
        }

        $makam->update($validated);
        ActivityLogService::log('makam_update', $makam);

        return redirect()->route('admin.makam.index')->with('success', 'Data makam berhasil diperbarui.');
    }

    public function destroy(Makam $makam)
    {
        if (!auth('admin')->user()?->isSuperAdmin()) {
            return redirect()->route('admin.makam.index')->with('error', 'Admin tidak diizinkan menghapus data.');
        }

        if ($makam->foto) {
            Storage::disk('public')->delete($makam->foto);
        }
        if ($makam->cover) {
            Storage::disk('public')->delete($makam->cover);
        }

        ActivityLogService::log('makam_delete', $makam);
        $makam->delete();

        return redirect()->route('admin.makam.index')->with('success', 'Data makam berhasil dihapus.');
    }
}
