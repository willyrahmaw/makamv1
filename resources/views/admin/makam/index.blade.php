@extends('layouts.admin')

@section('title', 'Data Makam')
@section('page-title', 'Kelola Data Makam')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-archive me-2"></i>Daftar Data Makam</span>
        <a href="{{ route('admin.makam.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Makam
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, nama ayah, ahli waris..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <label for="blok_filter_index" class="form-label visually-hidden">Filter Blok</label>
                <select name="blok_id" id="blok_filter_index" class="form-select" aria-label="Filter berdasarkan blok">
                    <option value="">-- Semua Blok --</option>
                    @foreach($bloks as $blok)
                        <option value="{{ $blok->id }}" {{ request('blok_id') == $blok->id ? 'selected' : '' }}>
                            {{ $blok->nama_blok }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Tgl Wafat</th>
                        <th>Usia</th>
                        <th>Blok</th>
                        <th>Ahli Waris</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($makam as $m)
                    <tr>
                        <td>{{ $loop->iteration + ($makam->currentPage() - 1) * $makam->perPage() }}</td>
                        <td>
                            <div class="fw-medium">{{ $m->nama_lengkap ?? '-' }}</div>
                            <small class="text-muted">{{ $m->jenis_kelamin === 'laki-laki' ? 'bin' : ($m->jenis_kelamin === 'perempuan' ? 'binti' : '') }} {{ $m->nama_ayah ?? '-' }}</small>
                        </td>
                        <td>
                            @if($m->jenis_kelamin === 'laki-laki')
                                <span class="badge bg-primary">L</span>
                            @elseif($m->jenis_kelamin === 'perempuan')
                                <span class="badge bg-danger">P</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ $m->tanggal_wafat?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $m->usia !== null ? $m->usia . ' th' : '-' }}</td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $m->blok?->nama_blok ?? '-' }}</span>
                            @if($m->nomor_makam)
                                <br><small class="text-muted">No: {{ $m->nomor_makam }}</small>
                            @endif
                        </td>
                        <td>{{ $m->ahli_waris ?? '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.makam.show', $m) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.makam.edit', $m) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.makam.destroy', $m) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-delete-confirm" title="Hapus" data-message="Yakin ingin menghapus data makam ini?">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mb-0 mt-2">Belum ada data makam</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $makam->withQueryString()->links() }}
    </div>
</div>
@endsection
