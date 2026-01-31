@extends('layouts.public')

@section('title', $blok->nama_blok . ' - Digitalisasi Makam')

@push('styles')
<style>
.blok-header-card {
    border-left: 5px solid var(--accent);
    background: linear-gradient(135deg, var(--cream) 0%, #ffffff 100%);
}

.blok-color-indicator {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 3px solid var(--primary);
    display: inline-block;
    vertical-align: middle;
}

.search-form-blok {
    background: var(--cream);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
}
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('denah') }}">Denah</a></li>
        <li class="breadcrumb-item active">{{ $blok->nama_blok }}</li>
    </ol>
</nav>

<!-- Blok Header -->
<div class="card blok-header-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="blok-color-indicator me-3" style="background: {{ $warnaBlok }};"></div>
                    <div>
                        <h2 class="mb-1" style="color: var(--primary); font-family: 'Playfair Display', serif;">
                            <i class="bi bi-grid-3x3-gap me-2"></i>{{ $blok->nama_blok }}
                        </h2>
                        @if($blok->keterangan)
                        <p class="text-muted mb-0">{{ $blok->keterangan }}</p>
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 gap-md-3">
                    <div>
                        <span class="badge bg-primary" style="font-size: 1rem; padding: 8px 16px;">
                            <i class="bi bi-archive me-1"></i>{{ $jumlahMakam }} Makam
                        </span>
                    </div>
                    <div>
                        <span class="badge bg-success" style="font-size: 1rem; padding: 8px 16px;">
                            <i class="bi bi-person-check me-1"></i>{{ $terdata }} Terdata
                        </span>
                    </div>
                    <div>
                        <span class="badge bg-secondary" style="font-size: 1rem; padding: 8px 16px;">
                            <i class="bi bi-person-x me-1"></i>{{ $tidakDikenali }} Tidak Dikenali
                        </span>
                    </div>
                    <div>
                        <span class="badge" style="background: var(--accent); font-size: 1rem; padding: 8px 16px;">
                            <i class="bi bi-palette me-1"></i>
                            @if($jumlahMakam >= $thresholdMerah)
                                Penuh
                            @elseif($jumlahMakam >= $thresholdKuning)
                                Lumayan
                            @elseif($jumlahMakam > 0)
                                Ada
                            @else
                                Kosong
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('denah') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Denah
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="search-form-blok">
    <h3 class="mb-3"><i class="bi bi-search me-2"></i>Cari Makam di {{ $blok->nama_blok }}</h3>
    <form method="GET" action="{{ route('blok.show', $blok) }}">
        <div class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="Cari nama, nama ayah, ahli waris, atau nomor makam..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-funnel me-1"></i> Cari
                </button>
            </div>
        </div>
        @if(request('search'))
        <div class="mt-2">
            <a href="{{ route('blok.show', $blok) }}" class="text-decoration-none">
                <i class="bi bi-x-circle me-1"></i> Hapus filter
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Results -->
@if($makam->count() > 0)
<div class="mb-3">
    <p class="text-muted mb-0">
        @if(request('search'))
            Ditemukan <strong>{{ $makam->total() }}</strong> data makam dari pencarian "{{ request('search') }}"
        @else
            Total <strong>{{ $makam->total() }}</strong> data makam di {{ $blok->nama_blok }}
        @endif
    </p>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th style="width: 80px;">No. Makam</th>
                <th>Nama Almarhum/Almarhumah</th>
                <th style="width: 120px;">Jenis Kelamin</th>
                <th style="width: 120px;">Tanggal Wafat</th>
                <th style="width: 100px;">Usia</th>
                <th style="width: 100px;">Catatan</th>
                <th style="width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($makam as $m)
            <tr>
                <td>
                    <span class="badge" style="background: var(--primary);">
                        {{ $m->nomor_makam ?: '-' }}
                    </span>
                </td>
                <td>
                    <strong>{{ $m->nama_lengkap_bin_binti }}</strong>
                </td>
                <td>
                    <i class="bi bi-gender-{{ $m->jenis_kelamin == 'laki-laki' ? 'male' : 'female' }} me-1"></i>
                    {{ ucfirst($m->jenis_kelamin) }}
                </td>
                <td>{{ $m->tanggal_wafat?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $m->usia ? $m->usia . ' th' : '-' }}</td>
                <td>
                    @if($m->catatan)
                    <small class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $m->catatan }}">
                        <i class="bi bi-info-circle"></i> {{ Str::limit($m->catatan, 20) }}
                    </small>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('detail', $m) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye me-1"></i> Detail
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $makam->withQueryString()->links() }}
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">
            @if(request('search'))
                Data tidak ditemukan
            @else
                Belum ada data makam di {{ $blok->nama_blok }}
            @endif
        </h4>
        <p class="text-muted">
            @if(request('search'))
                Coba gunakan kata kunci pencarian yang berbeda
            @else
                Data makam akan muncul di sini setelah ditambahkan oleh admin
            @endif
        </p>
        @if(request('search'))
        <a href="{{ route('blok.show', $blok) }}" class="btn btn-outline-primary mt-2">
            <i class="bi bi-arrow-left me-1"></i> Lihat Semua Makam
        </a>
        @endif
    </div>
</div>
@endif

<!-- Navigasi Blok Lainnya -->
<div class="card mt-5">
    <div class="card-header">
        <h3 class="mb-0"><i class="bi bi-grid-3x3 me-2"></i>Blok Lainnya</h3>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @foreach($allBloks as $b)
            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ route('blok.show', $b) }}" 
                   class="btn btn-outline-primary w-100 {{ $b->id == $blok->id ? 'active' : '' }}">
                    {{ $b->nama_blok }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
@endpush
