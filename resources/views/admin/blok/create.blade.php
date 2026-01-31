@extends('layouts.admin')

@section('title', 'Tambah Blok')
@section('page-title', 'Tambah Blok Makam')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-grid-3x3-gap me-2"></i>Formulir Blok Makam Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.blok.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Blok <span class="text-danger">*</span></label>
                        <input type="text" name="nama_blok" class="form-control @error('nama_blok') is-invalid @enderror" 
                               value="{{ old('nama_blok') }}" placeholder="Contoh: Blok A1, Blok Pendopo" required>
                        @error('nama_blok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2" placeholder="Keterangan lokasi blok (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan
                        </button>
                        <a href="{{ route('admin.blok.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
