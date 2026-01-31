@extends('layouts.admin')

@section('title', 'Tambah Transaksi Keuangan')
@section('page-title', 'Tambah Transaksi Keuangan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i>Transaksi Baru
            </div>
            <div class="card-body">
                <form action="{{ route('admin.keuangan.store') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="text" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', now()->format('d/m/Y')) }}" placeholder="dd/mm/yyyy" autocomplete="off" required>
                            <small class="text-muted">Contoh: 28/01/2025</small>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tipe_create" class="form-label">Tipe <span class="text-danger">*</span></label>
                            <select name="tipe" id="tipe_create" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="pemasukan" {{ old('tipe') === 'pengeluaran' ? '' : 'selected' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ old('tipe') === 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                            @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nominal" step="0.01" min="0" class="form-control @error('nominal') is-invalid @enderror"
                                   value="{{ old('nominal') }}" required>
                            @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Sumber / Kategori</label>
                            <input type="text" name="sumber" class="form-control @error('sumber') is-invalid @enderror"
                                   value="{{ old('sumber') }}" placeholder="Contoh: Donasi Jamaah, Perawatan Makam">
                            @error('sumber')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Metode</label>
                            <input type="text" name="metode" class="form-control @error('metode') is-invalid @enderror"
                                   value="{{ old('metode') }}" placeholder="Tunai, Transfer, QRIS">
                            @error('metode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">No. Referensi</label>
                            <input type="text" name="referensi" class="form-control @error('referensi') is-invalid @enderror"
                                   value="{{ old('referensi') }}" placeholder="No. bukti / transaksi">
                            @error('referensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Donatur</label>
                            <input type="text" name="donatur" class="form-control @error('donatur') is-invalid @enderror"
                                   value="{{ old('donatur') }}" placeholder="Diisi untuk donasi">
                            @error('donatur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="2" class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Keterangan singkat penggunaan dana / sumber dana">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                        <a href="{{ route('admin.keuangan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

