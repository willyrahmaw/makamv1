@extends('layouts.admin')

@section('title', 'Edit Blok')
@section('page-title', 'Edit Blok Makam')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2"></i>Edit: {{ $blok->nama_blok }}
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>Blok ini memiliki <strong>{{ $blok->makam()->count() }} makam</strong> tercatat.
                </div>

                <form action="{{ route('admin.blok.update', $blok) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Blok <span class="text-danger">*</span></label>
                        <input type="text" name="nama_blok" class="form-control @error('nama_blok') is-invalid @enderror" 
                               value="{{ old('nama_blok', $blok->nama_blok) }}" required>
                        @error('nama_blok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan', $blok->keterangan) }}</textarea>
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
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
