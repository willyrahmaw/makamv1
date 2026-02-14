@extends('layouts.admin')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-key-fill me-2"></i>Ganti Password Akun
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Gunakan form berikut untuk mengubah password akun Anda. Setelah berhasil, gunakan password baru saat login.</p>
                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password saat ini <span class="text-danger">*</span></label>
                        <input type="password" name="password_current" class="form-control @error('password_current') is-invalid @enderror"
                               required autocomplete="current-password">
                        @error('password_current')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               required autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 8 karakter, harus ada huruf besar, huruf kecil, dan minimal satu simbol (mis. @#$%^&*).</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi password baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Password
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
