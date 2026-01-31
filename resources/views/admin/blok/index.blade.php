@extends('layouts.admin')

@section('title', 'Blok Makam')
@section('page-title', 'Kelola Blok Makam')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-grid-3x3-gap me-2"></i>Daftar Blok Makam</span>
        <a href="{{ route('admin.blok.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Blok
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Blok</th>
                        <th>Keterangan</th>
                        <th>Jumlah Makam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bloks as $blok)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-medium">{{ $blok->nama_blok }}</td>
                        <td>{{ $blok->keterangan ?? '-' }}</td>
                        <td><span class="badge bg-primary">{{ $blok->makam_count }} makam</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.blok.edit', $blok) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.blok.destroy', $blok) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-delete-confirm" title="Hapus" data-message="Yakin ingin menghapus blok ini? Data makam di blok ini juga akan terpengaruh.">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-grid-3x3" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">Belum ada blok makam</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
