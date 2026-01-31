@extends('layouts.admin')

@section('title', 'Tambah Data Makam')
@section('page-title', 'Tambah Data Makam')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus me-2"></i>Formulir Data Makam Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.makam.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h6 class="text-muted mb-3"><i class="bi bi-person me-2"></i>Data Almarhum/Almarhumah</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="dikenaliSwitch" name="dikenali" value="1"
                                       {{ old('dikenali', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="dikenaliSwitch">
                                    Makam dikenali (jika dimatikan = tanpa nama / tidak diketahui)
                                </label>
                            </div>
                            <small class="text-muted">Jika tidak dikenali, sistem akan menyimpan nama sebagai "Tidak diketahui".</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="nama_lengkap" id="namaLengkapInput" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   value="{{ old('nama_lengkap') }}" placeholder="Kosongkan = Tidak diketahui">
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jenisKelamin" class="form-label">Jenis Kelamin <span class="text-muted">(opsional)</span></label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenisKelamin">
                                <option value="">-- Pilih --</option>
                                <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                <option value="tidak-diketahui" {{ old('jenis_kelamin') == 'tidak-diketahui' ? 'selected' : '' }}>Tidak diketahui</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Ayah <span id="binBintiLabel" class="text-muted">(bin/binti)</span></label>
                            <input type="text" name="nama_ayah" id="namaAyahInput" class="form-control @error('nama_ayah') is-invalid @enderror" 
                                   value="{{ old('nama_ayah') }}">
                            @error('nama_ayah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Maks. 15MB. Gambar disimpan dengan ukuran asli.</small>
                            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="text" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   value="{{ old('tanggal_lahir') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            <small class="text-muted">Contoh: 15/01/1950</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Wafat <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="tanggal_wafat" class="form-control @error('tanggal_wafat') is-invalid @enderror" 
                                   value="{{ old('tanggal_wafat') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                            <small class="text-muted">Contoh: 20/12/2024</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Usia</label>
                            <input type="text" id="usia-preview" class="form-control" disabled placeholder="Akan dihitung otomatis">
                            <small class="text-muted">Isi tanggal lahir dan wafat untuk menghitung usia</small>
                        </div>
                    </div>

                    <h6 class="text-muted mb-3"><i class="bi bi-geo-alt me-2"></i>Lokasi Makam</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="blok_id_create" class="form-label">Blok Makam <span class="text-muted">(opsional)</span></label>
                            <select name="blok_id" id="blok_id_create" class="form-select @error('blok_id') is-invalid @enderror">
                                <option value="">-- Pilih Blok --</option>
                                @foreach($bloks as $blok)
                                    <option value="{{ $blok->id }}" {{ old('blok_id') == $blok->id ? 'selected' : '' }}>
                                        {{ $blok->nama_blok }}
                                    </option>
                                @endforeach
                            </select>
                            @error('blok_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Makam</label>
                            <input type="text" name="nomor_makam" class="form-control @error('nomor_makam') is-invalid @enderror" 
                                   value="{{ old('nomor_makam') }}" placeholder="Contoh: A-001">
                            @error('nomor_makam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2" 
                                      placeholder="Digunakan seperlunya untuk menentukan tempat, contoh: berada di samping A3">{{ old('catatan') }}</textarea>
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h6 class="text-muted mb-3"><i class="bi bi-people me-2"></i>Data Ahli Waris</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Ahli Waris</label>
                            <input type="text" name="ahli_waris" class="form-control @error('ahli_waris') is-invalid @enderror" 
                                   value="{{ old('ahli_waris') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon Ahli Waris</label>
                            <input type="tel" name="telepon_ahli_waris" class="form-control @error('telepon_ahli_waris') is-invalid @enderror" 
                                   value="{{ old('telepon_ahli_waris') }}" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan
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
document.getElementById('jenisKelamin').addEventListener('change', function() {
    const label = document.getElementById('binBintiLabel');
    label.textContent = this.value === 'laki-laki' ? '(bin)' : this.value === 'perempuan' ? '(binti)' : '(bin/binti)';
});

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
</script>
@endpush
@endsection
