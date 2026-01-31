@extends('layouts.admin')

@section('title', 'Edit Data Makam')
@section('page-title', 'Edit Data Makam')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2"></i>Edit: {{ $makam->nama_lengkap }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.makam.update', $makam) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="text-muted mb-3"><i class="bi bi-person me-2"></i>Data Almarhum/Almarhumah</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="dikenaliSwitch" name="dikenali" value="1"
                                       {{ old('dikenali', $makam->dikenali ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="dikenaliSwitch">
                                    Makam dikenali (jika dimatikan = tanpa nama / tidak diketahui)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="nama_lengkap" id="namaLengkapInput" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   value="{{ old('nama_lengkap', $makam->nama_lengkap) }}" placeholder="Kosongkan = Tidak diketahui">
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jenisKelamin" class="form-label">Jenis Kelamin <span class="text-muted">(opsional)</span></label>
                            <select name="jenis_kelamin" id="jenisKelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="laki-laki" {{ old('jenis_kelamin', $makam->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('jenis_kelamin', $makam->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                <option value="tidak-diketahui" {{ old('jenis_kelamin', $makam->jenis_kelamin) == 'tidak-diketahui' ? 'selected' : '' }}>Tidak diketahui</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="namaAyahInput" class="form-control" value="{{ old('nama_ayah', $makam->nama_ayah) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto</label>
                            @if($makam->foto)
                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">Foto saat ini (ukuran asli)</small>
                                    <img src="{{ Storage::url($makam->foto) }}" class="img-thumbnail" style="max-height: 180px; object-fit: contain;">
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Maks. 15MB, ukuran asli. Kosongkan jika tidak ingin mengubah.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="text" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   value="{{ old('tanggal_lahir', $makam->tanggal_lahir?->format('d/m/Y')) }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            <small class="text-muted">Contoh: 15/01/1950</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Wafat <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="tanggal_wafat" class="form-control @error('tanggal_wafat') is-invalid @enderror" 
                                   value="{{ old('tanggal_wafat', $makam->tanggal_wafat?->format('d/m/Y')) }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            <small class="text-muted">Contoh: 20/12/2024</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Usia</label>
                            <input type="text" id="usia-preview" class="form-control" disabled 
                                   value="{{ $makam->usia ? $makam->usia . ' tahun' : 'Akan dihitung otomatis' }}">
                            <small class="text-muted">Isi tanggal lahir dan wafat untuk menghitung usia</small>
                        </div>
                    </div>

                    <h6 class="text-muted mb-3"><i class="bi bi-geo-alt me-2"></i>Lokasi Makam</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="blok_id_edit" class="form-label">Blok Makam <span class="text-muted">(opsional)</span></label>
                            <select name="blok_id" id="blok_id_edit" class="form-select">
                                @foreach($bloks as $blok)
                                    <option value="{{ $blok->id }}" {{ old('blok_id', $makam->blok_id) == $blok->id ? 'selected' : '' }}>
                                        {{ $blok->nama_blok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Makam</label>
                            <input type="text" name="nomor_makam" class="form-control" value="{{ old('nomor_makam', $makam->nomor_makam) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
                            <textarea name="catatan" class="form-control" rows="2" 
                                      placeholder="Digunakan seperlunya untuk menentukan tempat, contoh: berada di samping A3">{{ old('catatan', $makam->catatan) }}</textarea>
                        </div>
                    </div>

                    <h6 class="text-muted mb-3"><i class="bi bi-people me-2"></i>Data Ahli Waris</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Ahli Waris</label>
                            <input type="text" name="ahli_waris" class="form-control" value="{{ old('ahli_waris', $makam->ahli_waris) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon Ahli Waris</label>
                            <input type="tel" name="telepon_ahli_waris" class="form-control" value="{{ old('telepon_ahli_waris', $makam->telepon_ahli_waris) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $makam->keterangan) }}</textarea>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.makam.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Parse tanggal dd/mm/yyyy atau dd-mm-yyyy ke Date
function parseTanggal(str) {
    if (!str || !str.trim()) return null;
    const m = str.trim().match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
    if (m) return new Date(parseInt(m[3]), parseInt(m[2], 10) - 1, parseInt(m[1], 10));
    if (/^\d{4}-\d{2}-\d{2}$/.test(str)) return new Date(str);
    return new Date(str);
}

// Fungsi untuk menghitung usia
function hitungUsia() {
    const tanggalLahir = document.querySelector('input[name="tanggal_lahir"]').value;
    const tanggalWafat = document.querySelector('input[name="tanggal_wafat"]').value;
    const usiaPreview = document.getElementById('usia-preview');
    
    if (tanggalLahir && tanggalWafat) {
        const lahir = parseTanggal(tanggalLahir);
        const wafat = parseTanggal(tanggalWafat);
        if (!lahir || !wafat || isNaN(lahir.getTime()) || isNaN(wafat.getTime())) {
            usiaPreview.value = 'Format: dd/mm/yyyy';
            usiaPreview.style.color = '#6c757d';
            return;
        }
        if (wafat >= lahir) {
            let usia = wafat.getFullYear() - lahir.getFullYear();
            const bulan = wafat.getMonth() - lahir.getMonth();
            
            if (bulan < 0 || (bulan === 0 && wafat.getDate() < lahir.getDate())) {
                usia--;
            }
            
            if (usia >= 0) {
                usiaPreview.value = usia + ' tahun';
                usiaPreview.style.color = '#198754';
            } else {
                usiaPreview.value = 'Tanggal wafat harus setelah tanggal lahir';
                usiaPreview.style.color = '#dc3545';
            }
        } else {
            usiaPreview.value = 'Tanggal wafat harus setelah tanggal lahir';
            usiaPreview.style.color = '#dc3545';
        }
    } else {
        usiaPreview.value = '';
        usiaPreview.placeholder = 'Akan dihitung otomatis';
    }
}

// Event listener untuk menghitung usia saat input berubah
document.querySelector('input[name="tanggal_lahir"]').addEventListener('input', hitungUsia);
document.querySelector('input[name="tanggal_wafat"]').addEventListener('input', hitungUsia);

// Hitung usia saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    hitungUsia();
});
</script>
@endpush
@endsection
