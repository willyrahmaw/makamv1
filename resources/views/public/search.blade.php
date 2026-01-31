@extends('layouts.public')

@section('title', 'Cari Makam - Digitalisasi Makam')

@section('content')
<h1 class="mb-4" style="color: var(--primary);"><i class="bi bi-search me-2"></i>Cari Data Makam</h1>

<!-- Search Form -->
<div class="search-box">
    <form method="GET" action="{{ route('search') }}">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama, nama ayah, atau ahli waris..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label for="blok_id" class="form-label visually-hidden">Filter Blok</label>
                <select name="blok_id" id="blok_id" class="form-select" aria-label="Filter berdasarkan blok">
                    <option value="">-- Semua Blok --</option>
                    @foreach($bloks as $blok)
                        <option value="{{ $blok->id }}" {{ request('blok_id') == $blok->id ? 'selected' : '' }}>
                            {{ $blok->nama_blok }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Results -->
@if($makam->count() > 0)
<p class="text-muted mb-4">Ditemukan {{ $makam->total() }} data makam</p>
<div class="row g-4">
    @foreach($makam as $m)
    <div class="col-md-6 col-lg-4">
        <div class="card makam-card h-100">
            @if($m->foto)
                <img src="{{ Storage::url($m->foto) }}" class="card-img-top" alt="{{ $m->nama_lengkap }}" style="object-fit: contain; max-height: 280px; width: 100%;">
            @else
                <div class="placeholder-img">
                    <i class="bi bi-person"></i>
                </div>
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="nama">{{ $m->nama_lengkap_bin_binti }}</h5>
                <p class="info mb-1">
                    @if($m->jenis_kelamin)
                    <i class="bi bi-gender-{{ $m->jenis_kelamin === 'laki-laki' ? 'male' : 'female' }} me-1"></i>
                    {{ ucfirst(str_replace('-', ' ', $m->jenis_kelamin)) }}
                    @else
                    <i class="bi bi-gender-ambiguous me-1"></i> -
                    @endif
                </p>
                <p class="info mb-1">
                    <i class="bi bi-calendar3 me-1"></i>
                    Wafat: {{ $m->tanggal_wafat?->format('d/m/Y') ?? '-' }}
                </p>
                @if($m->usia)
                <p class="info mb-0">
                    <i class="bi bi-hourglass me-1"></i> Usia: {{ $m->usia }} tahun
                </p>
                @endif
                <div class="lokasi">
                    <i class="bi bi-geo-alt me-1"></i> {{ $m->lokasi_makam }}
                    @if($m->catatan)
                    <br>
                    <small class="text-muted mt-1 d-block">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ Str::limit($m->catatan, 50) }}
                    </small>
                    @endif
                </div>
                <div class="mt-auto pt-3">
                    <a href="{{ route('detail', $m) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-eye me-1"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $makam->withQueryString()->links() }}
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
        <h4 class="mt-3 text-muted">
            @if(request('search') || request('blok_id'))
                Data tidak ditemukan
            @else
                Belum ada data makam
            @endif
        </h4>
        <p class="text-muted">Coba gunakan kata kunci pencarian yang berbeda</p>
    </div>
</div>
@endif
@endsection
