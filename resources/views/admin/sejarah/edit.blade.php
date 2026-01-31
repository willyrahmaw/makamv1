@extends('layouts.admin')

@section('title', 'Edit Sejarah')
@section('page-title', 'Edit Sejarah Desa')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2"></i>Edit Sejarah
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sejarah.update', $sejarah) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Konten Sejarah <span class="text-danger">*</span></label>
                        <textarea name="konten" class="form-control @error('konten') is-invalid @enderror" rows="15" required>{{ old('konten', $sejarah->konten) }}</textarea>
                        @error('konten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Gunakan HTML untuk format teks (bold, italic, list, dll)</small>
                    </div>

                    <h6 class="text-muted mb-3 mt-4"><i class="bi bi-person me-2"></i>Informasi Narasumber</h6>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Narasumber</label>
                            <input type="text" name="narasumber_nama" class="form-control" 
                                   value="{{ old('narasumber_nama', $sejarah->narasumber_nama) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="text" name="narasumber_lahir" class="form-control" 
                                   value="{{ old('narasumber_lahir', $sejarah->narasumber_lahir) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jabatan/Status</label>
                            <input type="text" name="narasumber_jabatan" class="form-control" 
                                   value="{{ old('narasumber_jabatan', $sejarah->narasumber_jabatan) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="aktif" class="form-check-input" id="aktif" value="1" 
                                   {{ old('aktif', $sejarah->aktif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">
                                <strong>Tampilkan di halaman beranda</strong>
                            </label>
                        </div>
                        <small class="text-muted">Hanya satu sejarah yang bisa aktif. Jika ini diaktifkan, sejarah lain akan dinonaktifkan.</small>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.sejarah.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
