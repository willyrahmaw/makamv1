@extends('layouts.public')

@section('title', $makam->nama_lengkap . ' - Digitalisasi Makam')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('search') }}">Cari Makam</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card" style="border-radius: 20px; overflow: hidden;">
            <div class="row g-0">
                <!-- Foto -->
                <div class="col-md-4">
                    @if($makam->foto)
                        <img src="{{ Storage::url($makam->foto) }}" class="img-fluid" style="width: 100%; height: auto; object-fit: contain;" alt="{{ $makam->nama_lengkap ?? 'Makam' }}">
                    @else
                        <div class="h-100 d-flex align-items-center justify-content-center" 
                             style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); min-height: 300px;">
                            <i class="bi bi-person" style="font-size: 5rem; color: var(--gold);"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Info -->
                <div class="col-md-8">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="mb-1" style="font-family: 'Playfair Display', serif; color: var(--primary);">
                                    {{ $makam->nama_lengkap_bin_binti }}
                                </h2>
                                <span class="badge bg-{{ $makam->jenis_kelamin == 'laki-laki' ? 'primary' : 'danger' }}">
                                    {{ ucfirst($makam->jenis_kelamin) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-4 p-3 rounded" style="background: var(--cream);">
                            <i class="bi bi-geo-alt me-2" style="color: var(--accent);"></i>
                            <strong>{{ $makam->lokasi_makam }}</strong>
                            @if($makam->catatan)
                            <div class="mt-2 p-2 rounded" style="background: rgba(26, 188, 156, 0.1); border-left: 3px solid var(--accent);">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Catatan:</strong> {{ $makam->catatan }}
                                </small>
                            </div>
                            @endif
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-sm-6">
                                <div class="text-muted small text-uppercase">Tanggal Lahir</div>
                                <div class="fw-medium">{{ $makam->tanggal_lahir ? $makam->tanggal_lahir->format('d F Y') : '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small text-uppercase">Tanggal Wafat</div>
                                <div class="fw-medium">{{ $makam->tanggal_wafat?->format('d F Y') ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small text-uppercase">Usia</div>
                                <div class="fw-medium">{{ $makam->usia ? $makam->usia . ' tahun' : '-' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted small text-uppercase">Nama Ayah</div>
                                <div class="fw-medium">{{ $makam->nama_ayah ?? '-' }}</div>
                            </div>
                        </div>

                        @if($makam->ahli_waris)
                        <hr>
                        <h6 class="text-muted mb-3"><i class="bi bi-people me-2"></i>Informasi Ahli Waris</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="text-muted small text-uppercase">Nama</div>
                                <div class="fw-medium">{{ $makam->ahli_waris }}</div>
                            </div>
                            @if($makam->telepon_ahli_waris)
                            <div class="col-sm-6">
                                <div class="text-muted small text-uppercase">Telepon</div>
                                <div class="fw-medium">{{ $makam->telepon_ahli_waris }}</div>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($makam->keterangan)
                        <hr>
                        <h6 class="text-muted mb-2"><i class="bi bi-info-circle me-2"></i>Keterangan</h6>
                        <p class="mb-0">{{ $makam->keterangan }}</p>
                        @endif

                        <hr>
                        <a href="{{ route('search') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Pencarian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
