@extends('layouts.public')

@section('title', 'Peta & Layanan Kelurahan')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h2 class="mb-4" style="color: var(--primary);">
            <i class="bi bi-geo-alt-fill me-2"></i>Peta Lokasi
        </h2>
        <div class="card mb-4">
            <div class="card-body p-0" style="border-radius: 16px; overflow: hidden;">
                @if($mapEmbedUrl)
                    <div class="ratio ratio-16x9">
                        <iframe
                            src="{{ $mapEmbedUrl }}"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Peta Lokasi Kelurahan"
                            class="border-0"
                            style="border-radius: 0 0 16px 16px;"></iframe>
                    </div>
                @else
                    <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                        <div class="text-center text-muted p-4">
                            <i class="bi bi-map" style="font-size: 4rem;"></i>
                            <p class="mt-3 mb-0">Peta belum diatur.</p>
                            <p class="small mb-0">Admin dapat mengisi URL embed Google Maps di Pengaturan Website.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <h2 class="mb-4" style="color: var(--primary);">
            <i class="bi bi-building me-2"></i>Layanan Kelurahan
        </h2>
        <div class="card">
            <div class="card-body">
                @if($layananKelurahan)
                    @php
                        $layanan = array_filter(array_map('trim', explode("\n", $layananKelurahan)));
                    @endphp
                    <ul class="list-unstyled mb-0">
                        @foreach($layanan as $item)
                        <li class="d-flex align-items-center py-2 border-bottom border-light">
                            <i class="bi bi-check2-circle text-success me-2" style="font-size: 1.1rem;"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">Daftar layanan kelurahan dapat diatur oleh admin di Pengaturan Website.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
