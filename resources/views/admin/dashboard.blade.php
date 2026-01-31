@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-archive"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalMakam }}</h3>
                <p>Total Makam</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-grid-3x3-gap"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalBlok }}</h3>
                <p>Blok Makam</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $makamBulanIni }}</h3>
                <p>Makam Bulan Ini</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning-fill me-2"></i>Aksi Cepat
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.makam.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Data Makam
                    </a>
                    <a href="{{ route('admin.blok.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Tambah Blok Baru
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary" target="_blank">
                        <i class="bi bi-globe me-2"></i>Lihat Website Publik
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistik Blok -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill me-2"></i>Jumlah per Blok
            </div>
            <div class="card-body">
                @foreach($bloks as $blok)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="fw-medium">{{ $blok->nama_blok }}</span>
                    <span class="badge bg-primary">{{ $blok->makam_count }} makam</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Makam Terbaru -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Data Makam Terbaru</span>
                <a href="{{ route('admin.makam.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Blok</th>
                                <th>Tgl Wafat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($makamTerbaru as $m)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $m->nama_lengkap ?? '-' }}</div>
                                    <small class="text-muted">{{ $m->jenis_kelamin === 'laki-laki' ? 'bin' : ($m->jenis_kelamin === 'perempuan' ? 'binti' : '') }} {{ $m->nama_ayah ?? '-' }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $m->blok?->nama_blok ?? '-' }}</span></td>
                                <td>{{ $m->tanggal_wafat?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.makam.edit', $m) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data makam</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
