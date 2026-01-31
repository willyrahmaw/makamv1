@extends('layouts.public')

@section('title', 'Beranda - Digitalisasi Makam')

@section('hero')
<div class="hero">
    <div class="container">
        <h1 class="display-4">Sistem Digitalisasi Makam</h1>
        <p class="lead mb-4">Cari informasi makam dengan mudah dan cepat</p>
        <a href="{{ route('search') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-search me-2"></i>Cari Makam
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Sejarah -->
@if($sejarah)
<div class="card mb-5" style="border-left: 4px solid var(--accent);">
    <div class="card-body p-4">
        <h2 class="mb-4" style="color: var(--primary); font-family: 'Playfair Display', serif;">
            <i class="bi bi-book me-2"></i>Sejarah Ngadirejo
        </h2>
        <div class="row">
            <div class="col-lg-8">
                <div class="sejarah-konten text-muted" style="line-height: 1.8;">
                    {!! nl2br(e($sejarah->konten)) !!}
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="p-4 rounded" style="background: var(--cream);">
                    <i class="bi bi-flower1" style="font-size: 4rem; color: var(--accent);"></i>
                    <p class="mt-3 mb-2 text-muted small">
                        <strong>"Ben Ngadi Lan Rejo"</strong>
                    </p>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                        Harapan agar desa selalu hidup, makmur, dan sejahtera
                    </p>
                </div>
                @if($sejarah->narasumber_nama)
                <div class="mt-4 p-3 rounded" style="background: var(--cream);">
                    <h6 class="text-muted mb-2">Narasumber</h6>
                    <p class="mb-1"><strong>{{ $sejarah->narasumber_nama }}</strong></p>
                    @if($sejarah->narasumber_lahir)
                        <p class="text-muted small mb-0">
                            @if($sejarah->narasumber_lahir)
                                Lahir: {{ $sejarah->narasumber_lahir }}<br>
                            @endif
                            @if($sejarah->narasumber_jabatan)
                                {{ $sejarah->narasumber_jabatan }}
                            @endif
                        </p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Stats -->
<div class="row g-4 mb-5">
    <div class="col-6 col-md-3">
        <div class="stats-widget">
            <div class="stats-icon"><i class="bi bi-archive"></i></div>
            <div class="stats-value">{{ $totalMakam }}</div>
            <div class="stats-label">Total Data Makam</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-widget">
            <div class="stats-icon"><i class="bi bi-grid-3x3-gap"></i></div>
            <div class="stats-value">{{ $totalBlok }}</div>
            <div class="stats-label">Blok Tersedia</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-widget">
            <div class="stats-icon"><i class="bi bi-person-check"></i></div>
            <div class="stats-value">{{ $terdata }}</div>
            <div class="stats-label">Terdata (Dikenali)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stats-widget">
            <div class="stats-icon"><i class="bi bi-person-x"></i></div>
            <div class="stats-value">{{ $tidakDikenali }}</div>
            <div class="stats-label">Tidak Dikenali</div>
        </div>
    </div>
</div>

<!-- Quick Search -->
<div class="search-box mb-5">
    <h4 class="mb-3"><i class="bi bi-search me-2"></i>Pencarian Cepat</h4>
    <form action="{{ route('search') }}" method="GET">
        <div class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="Masukkan nama almarhum/almarhumah...">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search me-2"></i>Cari
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Denah Makam -->
@php
    use App\Models\Settings;
    
    // Ambil settings warna blok
    $warnaMerah = Settings::get('blok_warna_merah', '#FF6B6B');
    $warnaKuning = Settings::get('blok_warna_kuning', '#FFD93D');
    $warnaHijau = Settings::get('blok_warna_hijau', '#6BCF7F');
    $warnaPutih = Settings::get('blok_warna_putih', '#FFFFFF');
    $thresholdMerah = (int) Settings::get('blok_threshold_merah', '10');
    $thresholdKuning = (int) Settings::get('blok_threshold_kuning', '5');
@endphp

<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--primary);"><i class="bi bi-map me-2"></i>Denah Lokasi Makam</h2>
        <a href="{{ route('denah') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-right me-1"></i> Lihat Detail Denah
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <p class="text-muted mb-3"><i class="bi bi-hand-index me-2"></i>Klik blok untuk melihat detail di halaman denah</p>
            
            @include('public.partials.denah-2d', ['bloks' => $bloksForDenah, 'variant' => 'home'])

            <!-- Legend -->
            <div class="denah-legend-home mt-4">
                <h6><i class="bi bi-info-circle me-2"></i>Keterangan Warna Blok:</h6>
                <div class="d-flex flex-wrap gap-3">
                    <div class="legend-item-home">
                        <div class="legend-color-home" style="background: {{ $warnaMerah }};"></div>
                        <span>Merah (Penuh) - &ge; {{ $thresholdMerah }} makam</span>
                    </div>
                    <div class="legend-item-home">
                        <div class="legend-color-home" style="background: {{ $warnaKuning }};"></div>
                        <span>Kuning (Lumayan) - {{ $thresholdKuning }}-{{ $thresholdMerah-1 }} makam</span>
                    </div>
                    <div class="legend-item-home">
                        <div class="legend-color-home" style="background: {{ $warnaHijau }};"></div>
                        <span>Hijau (Ada) - 1-{{ $thresholdKuning-1 }} makam</span>
                    </div>
                    <div class="legend-item-home">
                        <div class="legend-color-home" style="background: {{ $warnaPutih }}; border: 2px solid #ccc;"></div>
                        <span>Putih (Kosong) - 0 makam</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Makam Terbaru -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--primary);"><i class="bi bi-clock-history me-2"></i>Data Makam Terbaru</h2>
        <a href="{{ route('search') }}" class="btn btn-outline-primary">Lihat Semua</a>
    </div>
    @if($makamTerbaru->count() > 0)
    <div class="row g-4">
        @foreach($makamTerbaru as $m)
        <div class="col-md-6 col-lg-4">
            <div class="card makam-card h-100">
                @if($m->foto)
                    <img src="{{ Storage::url($m->foto) }}" class="card-img-top" alt="{{ $m->nama_lengkap ?? 'Makam' }}" style="object-fit: contain; max-height: 280px; width: 100%;">
                @else
                    <div class="placeholder-img">
                        <i class="bi bi-person"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="nama">{{ $m->nama_lengkap_bin_binti }}</h5>
                    @if($m->tanggal_wafat)
                    <p class="info mb-1">
                        <i class="bi bi-calendar3 me-1"></i>
                        Wafat: {{ $m->tanggal_wafat->format('d F Y') }}
                    </p>
                    @endif
                    @if($m->usia !== null)
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
                    <div class="mt-3">
                        <a href="{{ route('detail', $m) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
            <p class="text-muted mt-3 mb-0">Belum ada data makam terbaru.</p>
            <a href="{{ route('search') }}" class="btn btn-outline-primary mt-3">
                <i class="bi bi-search me-1"></i> Cari Makam
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Blok Info -->
<div class="mb-5">
    <h2 class="mb-4" style="color: var(--primary);"><i class="bi bi-grid-3x3 me-2"></i>Informasi Blok Makam</h2>
    <div class="row g-4" id="blok-container">
        @php
            $bloksToShow = 8; // Tampilkan 8 blok pertama
            $bloksVisible = $bloks->take($bloksToShow);
            $bloksHidden = $bloks->skip($bloksToShow);
        @endphp
        @foreach($bloksVisible as $blok)
        <div class="col-md-4 col-lg-3 blok-item">
            <a href="{{ route('blok.show', $blok) }}" 
               class="card text-center p-3 text-decoration-none blok-card" 
               style="transition: all 0.3s ease; color: inherit;">
                <div class="mb-2">
                    <i class="bi bi-grid-3x3-gap" style="font-size: 2rem; color: var(--accent);"></i>
                </div>
                <h5 class="mb-1" style="color: var(--primary);">{{ $blok->nama_blok }}</h5>
                <p class="text-muted small mb-2">{{ $blok->keterangan }}</p>
                <span class="badge" style="background: var(--accent);">{{ $blok->makam_count }} Makam</span>
            </a>
        </div>
        @endforeach
        @foreach($bloksHidden as $blok)
        <div class="col-md-4 col-lg-3 blok-item" style="display: none;">
            <a href="{{ route('denah') }}#blok-{{ strtolower(str_replace(' ', '-', $blok->nama_blok)) }}" 
               class="card text-center p-3 text-decoration-none blok-card" 
               style="transition: all 0.3s ease; color: inherit;">
                <div class="mb-2">
                    <i class="bi bi-grid-3x3-gap" style="font-size: 2rem; color: var(--accent);"></i>
                </div>
                <h5 class="mb-1" style="color: var(--primary);">{{ $blok->nama_blok }}</h5>
                <p class="text-muted small mb-2">{{ $blok->keterangan }}</p>
                <span class="badge" style="background: var(--accent);">{{ $blok->makam_count }} Makam</span>
            </a>
        </div>
        @endforeach
    </div>
    @if($bloksHidden->count() > 0)
    <div class="text-center mt-4">
        <button type="button" class="btn btn-outline-primary btn-lg" id="load-more-blok">
            <span>Lihat Semua Blok</span>
            <i class="bi bi-arrow-down ms-2"></i>
        </button>
    </div>
    @endif
</div>

@push('styles')
<style>
.blok-card {
    border: 2px solid transparent;
    border-radius: 8px;
}

.blok-card:hover {
    border-color: var(--accent);
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.blok-card h5 {
    transition: color 0.3s ease;
}

.blok-card:hover h5 {
    color: var(--accent) !important;
}

#load-more-blok {
    min-width: 200px;
    transition: all 0.3s ease;
}

#load-more-blok:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

#load-more-blok i {
    transition: transform 0.3s ease;
}

#load-more-blok:hover i {
    transform: translateY(3px);
}

.denah-legend-home {
    padding: 15px;
    background: var(--cream);
    border-radius: 10px;
}

.legend-item-home {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color-home {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    border: 2px solid var(--primary);
}

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more-blok');
    const hiddenBloks = document.querySelectorAll('.blok-item[style*="display: none"]');
    
    if (loadMoreBtn && hiddenBloks.length > 0) {
        loadMoreBtn.addEventListener('click', function() {
            // Tampilkan semua blok yang tersembunyi
            hiddenBloks.forEach(function(blok) {
                blok.style.display = '';
            });
            
            // Sembunyikan tombol load more
            loadMoreBtn.style.display = 'none';
            
            // Smooth scroll ke blok yang baru ditampilkan
            if (hiddenBloks.length > 0) {
                hiddenBloks[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    }
});
</script>
@endpush

<!-- Kontak Admin -->
@if($kontak)
<div class="card mb-5" style="border-left: 4px solid var(--primary); background: linear-gradient(135deg, var(--cream) 0%, #ffffff 100%);">
    <div class="card-body p-4">
        <h2 class="mb-4" style="color: var(--primary); font-family: 'Playfair Display', serif;">
            <i class="bi bi-person-badge me-2"></i>Kontak Admin
        </h2>
        <div class="row g-4">
            <div class="col-md-6">
                @if($kontak->telepon)
                <div class="d-flex align-items-start mb-3">
                    <div class="contact-icon me-3">
                        <i class="bi bi-telephone-fill" style="font-size: 1.5rem; color: var(--accent);"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: var(--primary);">Telepon</h6>
                        <p class="text-muted mb-0">
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $kontak->telepon) }}" class="text-decoration-none" style="color: var(--primary);">
                                {{ $kontak->telepon }}
                            </a>
                        </p>
                    </div>
                </div>
                @endif
                @if($kontak->email)
                <div class="d-flex align-items-start mb-3">
                    <div class="contact-icon me-3">
                        <i class="bi bi-envelope-fill" style="font-size: 1.5rem; color: var(--accent);"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: var(--primary);">Email</h6>
                        <p class="text-muted mb-0">
                            <a href="mailto:{{ $kontak->email }}" class="text-decoration-none" style="color: var(--primary);">
                                {{ $kontak->email }}
                            </a>
                        </p>
                    </div>
                </div>
                @endif
                @if($kontak->alamat)
                <div class="d-flex align-items-start">
                    <div class="contact-icon me-3">
                        <i class="bi bi-geo-alt-fill" style="font-size: 1.5rem; color: var(--accent);"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="color: var(--primary);">Alamat</h6>
                        <p class="text-muted mb-0">
                            {!! nl2br(e($kontak->alamat)) !!}
                        </p>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-6">
                <div class="p-4 rounded text-center" style="background: rgba(26, 188, 156, 0.1);">
                    <i class="bi bi-info-circle" style="font-size: 3rem; color: var(--accent);"></i>
                    <h6 class="mt-3 mb-2" style="color: var(--primary);">Butuh Bantuan?</h6>
                    <p class="text-muted small mb-3">
                        Untuk informasi lebih lanjut atau pertanyaan terkait data makam, silakan hubungi admin melalui kontak yang tersedia.
                    </p>
                    @if($kontak->jam_layanan)
                    <p class="text-muted small mb-0">
                        <i class="bi bi-clock me-1"></i>
                        <strong>Jam Layanan:</strong><br>
                        {!! nl2br(e($kontak->jam_layanan)) !!}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
