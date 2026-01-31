@extends('layouts.admin')

@section('title', 'Edit Kontak Admin')
@section('page-title', 'Edit Kontak Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-badge me-2"></i>Edit Kontak Admin
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.kontak.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                               value="{{ old('telepon', $kontak->telepon) }}" 
                               placeholder="Contoh: +62 812-3456-7890">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nomor telepon yang akan ditampilkan di halaman beranda</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $kontak->email) }}" 
                               placeholder="Contoh: admin@makam-ngadirejo.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Alamat email yang akan ditampilkan di halaman beranda</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="4" 
                                  placeholder="Contoh:&#10;Kantor Desa Ngadirejo&#10;Jl. Raya Ngadirejo, Kecamatan Ngadirejo&#10;Kabupaten Temanggung, Jawa Tengah">{{ old('alamat', $kontak->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Alamat lengkap kantor (gunakan baris baru untuk setiap baris alamat)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Layanan</label>
                        <textarea name="jam_layanan" class="form-control @error('jam_layanan') is-invalid @enderror" rows="3" 
                                  placeholder="Contoh:&#10;Senin - Jumat: 08:00 - 16:00 WIB&#10;Sabtu: 08:00 - 12:00 WIB">{{ old('jam_layanan', $kontak->jam_layanan) }}</textarea>
                        @error('jam_layanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Informasi jam layanan (gunakan baris baru untuk setiap baris)</small>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
