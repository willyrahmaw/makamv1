@extends('layouts.public')

@section('title', 'Denah Makam - Digitalisasi Makam')

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

@push('styles')
<style>
.denah-legend {
    padding: 15px;
    background: var(--cream);
    border-radius: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    border: 2px solid var(--primary);
}

/* Legend colors akan diatur dinamis */

.blok-table {
    scroll-margin-top: 100px;
    transition: all 0.3s ease;
}

.blok-table.highlight {
    box-shadow: 0 0 0 4px var(--accent), 0 8px 30px rgba(26, 188, 156, 0.3);
}
</style>
@endpush

@section('content')
<h1 class="mb-4" style="color: var(--primary);"><i class="bi bi-map me-2"></i>Denah Lokasi Makam</h1>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card">
            <div class="card-body">
                <!-- Compass -->
                <div class="mb-3">
                    <svg width="50" height="50" viewBox="0 0 50 50">
                        <polygon points="25,5 30,20 25,15 20,20" fill="var(--primary)"/>
                        <text x="25" y="45" text-anchor="middle" font-size="12" font-weight="bold">N</text>
                    </svg>
                </div>

                <p class="text-muted mb-3"><i class="bi bi-hand-index me-2"></i>Klik blok untuk melihat daftar makam</p>

                @include('public.partials.denah-2d', ['bloks' => $bloks, 'variant' => 'page'])

                <!-- Legend -->
                <div class="denah-legend mt-4">
                    <h6><i class="bi bi-info-circle me-2"></i>Keterangan Warna Blok:</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="legend-item">
                            <div class="legend-color" style="background: {{ $warnaMerah }};"></div>
                            <span>Merah (Penuh) - &ge; {{ $thresholdMerah }} makam</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: {{ $warnaKuning }};"></div>
                            <span>Kuning (Lumayan) - {{ $thresholdKuning }}-{{ $thresholdMerah-1 }} makam</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: {{ $warnaHijau }};"></div>
                            <span>Hijau (Ada) - 1-{{ $thresholdKuning-1 }} makam</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: {{ $warnaPutih }}; border: 2px solid #ccc;"></div>
                            <span>Putih (Kosong) - 0 makam</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Jumlah Makam per Blok</h5>
                @php
                    $blokGroups = $bloks->groupBy(function($blok) {
                        if (preg_match('/^Blok ([A-D])(\d+)$/', $blok->nama_blok, $matches)) {
                            return 'Blok ' . $matches[1];
                        }
                        return $blok->nama_blok;
                    });
                @endphp
                
                @foreach($blokGroups as $groupName => $groupBloks)
                <div class="mb-3 p-3 rounded" style="background: var(--cream);">
                    <h6 class="mb-2" style="color: var(--primary);">{{ $groupName }}</h6>
                    <div class="row g-2">
                        @foreach($groupBloks->sortBy('nama_blok') as $blok)
                        <div class="col-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small">{{ $blok->nama_blok }}</span>
                                <span class="badge bg-primary">{{ $blok->makam->count() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Daftar per Blok -->
<h3 class="mt-5 mb-4" style="color: var(--primary);"><i class="bi bi-list-ul me-2"></i>Daftar Makam per Blok</h3>

@foreach($bloks->sortBy('nama_blok') as $blok)
<div class="card mb-4 blok-table" id="blok-{{ strtolower(str_replace(' ', '-', $blok->nama_blok)) }}">
    <div class="card-header" style="background: var(--primary); color: white;">
        <i class="bi bi-grid-3x3-gap me-2"></i>{{ $blok->nama_blok }}
        @if($blok->keterangan)
            <small class="ms-2 text-light opacity-75">- {{ $blok->keterangan }}</small>
        @endif
        <span class="badge bg-light text-dark float-end">{{ $blok->makam->count() }} makam</span>
    </div>
    <div class="card-body p-0">
        @if($blok->makam->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama Almarhum/Almarhumah</th>
                        <th>Tgl Wafat</th>
                        <th>Usia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blok->makam as $m)
                    <tr>
                        <td>
                            <span class="badge" style="background: var(--primary);">{{ $m->nomor_makam ?: $loop->iteration }}</span>
                            @if($m->catatan)
                            <br><small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle"></i> {{ Str::limit($m->catatan, 30) }}
                            </small>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $m->nama_lengkap_bin_binti }}</strong>
                            <br><small class="text-muted">
                                <i class="bi bi-gender-{{ $m->jenis_kelamin == 'laki-laki' ? 'male' : 'female' }}"></i>
                                {{ ucfirst($m->jenis_kelamin) }}
                            </small>
                        </td>
                        <td>{{ $m->tanggal_wafat?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $m->usia ? $m->usia . ' th' : '-' }}</td>
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
        @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
            <p class="mt-2 mb-0">Blok ini belum memiliki data makam</p>
        </div>
        @endif
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
function scrollToBlok(blokId) {
    event.preventDefault();
    
    const element = document.getElementById(blokId);
    if (element) {
        // Remove highlight from all
        document.querySelectorAll('.blok-table').forEach(el => {
            el.classList.remove('highlight');
        });
        
        // Scroll to element
        element.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
        
        // Add highlight
        setTimeout(() => {
            element.classList.add('highlight');
            
            // Remove highlight after 2 seconds
            setTimeout(() => {
                element.classList.remove('highlight');
            }, 2000);
        }, 500);
    }
}
</script>
@endpush
