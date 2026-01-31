@php
use App\Models\Settings;

/**
 * Partial: Denah Makam (berdasarkan peta SVG custom)
 *
 * Params:
 * - $bloks: Collection BlokMakam (with makam)
 */

$warnaMerah   = Settings::get('blok_warna_merah', '#FF6B6B');
$warnaKuning = Settings::get('blok_warna_kuning', '#FFD93D');
$warnaHijau  = Settings::get('blok_warna_hijau', '#6BCF7F');
$warnaPutih  = Settings::get('blok_warna_putih', '#FFFFFF');
$warnaPendopo = Settings::get('blok_warna_pendopo', '#C4B896');

$thresholdMerah  = (int) Settings::get('blok_threshold_merah', 10);
$thresholdKuning = (int) Settings::get('blok_threshold_kuning', 5);

$blokMap = $bloks->keyBy('nama_blok');

$getWarna = function (int $count) use (
    $warnaPutih,
    $warnaHijau,
    $warnaKuning,
    $warnaMerah,
    $thresholdKuning,
    $thresholdMerah
) {
    return $count === 0
        ? $warnaPutih
        : ($count >= $thresholdMerah
            ? $warnaMerah
            : ($count >= $thresholdKuning ? $warnaKuning : $warnaHijau));
};

/**
 * Data polygon + label
 */
$polygons = [
    // A
    'A1' => ['points' => '60,760 220,760 240,650 80,680',  'text' => [150,710]],
    'A2' => ['points' => '80,680 240,650 270,520 130,430','text' => [150,590]],
    'A3' => ['points' => '130,430 270,520 330,410 210,380','text' => [250,450]],
    'A4' => ['points' => '210,380 330,410 360,320 250,230','text' => [270,340]],
    'A5' => ['points' => '250,230 360,320 440,110 330,160','text' => [310,240]],

    // B
    'B1' => ['points' => '220,760 430,720 440,650 240,650','text' => [320,700]],
    'B2' => ['points' => '240,650 440,650 460,520 270,520','text' => [330,600]],
    'B3' => ['points' => '270,520 460,520 480,400 330,410','text' => [370,480]],
    'B4' => ['points' => '330,410 480,400 480,310 360,320','text' => [400,360]],
    'B5' => ['points' => '360,320 480,310 500,20 440,110','text' => [415,250]],

    // C
    'C1' => ['points' => '430,720 630,720 630,640 440,650','text' => [520,680]],
    'C2' => ['points' => '440,650 630,640 620,520 460,520','text' => [520,600]],
    'C3' => ['points' => '460,520 620,520 620,380 480,400','text' => [520,470]],
    'C4' => ['points' => '480,400 620,380 620,310 480,310','text' => [520,350]],
    'C5' => ['points' => '480,310 620,310 600,160 500,20','text' => [520,240]],

    // D
    'D1' => ['points' => '630,720 820,690 810,600 630,640','text' => [710,680]],
    'D2' => ['points' => '630,640 810,600 790,520 620,520','text' => [700,580]],
    'D3' => ['points' => '620,520 790,520 780,380 620,380','text' => [690,460]],
    // Pendopo: sedikit ke bawah, sedikit melebar ke kiri
    'Pendopo' => ['points' => '560,415 670,415 670,510 560,510','text' => [560,460]],
    'D4' => ['points' => '620,380 780,380 760,290 620,310','text' => [680,340]],
    'D5' => ['points' => '620,310 760,290 750,230 600,160','text' => [660,250]],

    // E
    'E1' => ['points' => '820,690 1000,640 980,570 810,600','text' => [880,640]],
    'E2' => ['points' => '810,600 980,570 970,500 790,520','text' => [880,560]],
    'E3' => ['points' => '790,520 970,500 950,420 780,380','text' => [870,470]],
    'E4' => ['points' => '780,380 950,420 930,340 760,290','text' => [850,360]],
    'E5' => ['points' => '760,290 930,340 900,240 750,230','text' => [840,280]],
];
@endphp

<div class="denah2d">
    <div class="map-wrapper">
        <img src="{{ asset('images/peta13.png') }}" alt="Peta Makam">

        <svg viewBox="0 0 1100 850" aria-label="Denah Makam">
            <g transform="translate(0, -35)">
            @foreach ($polygons as $kode => $data)
                @php
                    $isPendopo = ($kode === 'Pendopo');
                    $blok = $blokMap->get('Blok ' . $kode);
                    $count = $blok?->makam->count() ?? 0;
                    $warna = $isPendopo ? $warnaPendopo : $getWarna($count);
                    $href  = $blok ? route('blok.show', $blok) : '#';
                @endphp

                <a href="{{ $href }}" class="denah2d__link {{ $isPendopo ? 'denah2d__link--pendopo' : '' }}" @if(!$blok) aria-disabled="true" tabindex="-1" @endif>
                    <polygon
                        points="{{ $data['points'] }}"
                        fill="{{ $warna }}"
                        stroke="#111"
                        stroke-width="4"
                        class="{{ ($blok || $isPendopo) ? '' : 'is-disabled' }}"
                    />
                    <text x="{{ $data['text'][0] }}" y="{{ $data['text'][1] }}">
                        {{ $kode }}
                    </text>
                </a>
            @endforeach
            </g>
        </svg>
    </div>
</div>

@once
@push('styles')
<style>
.denah2d {
    display: flex;
    justify-content: center;
    margin-top: -2rem;
}
.map-wrapper {
    position: relative;
    width: 85vw;
    max-width: 900px;
    height: 75vh;
    max-height: 650px;
}
.map-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}
.map-wrapper svg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
}

polygon {
    cursor: pointer;
    transition: filter .15s ease;
    /* bikin isi blok semi-transparan supaya peta di belakang kelihatan */
    fill-opacity: .45;
}
polygon:hover {
    filter: brightness(0.95);
    fill-opacity: .75;
}
polygon.is-disabled {
    fill: #e5e5e5 !important;
    cursor: default;
}
.denah2d__link--pendopo polygon {
    cursor: pointer;
}

text {
    fill: #fff;
    font-weight: 800;
    font-size: 28px;
    pointer-events: none;
    text-shadow: 2px 2px 5px rgba(0,0,0,.8);
}

@media (max-width: 768px) {
    text {
        font-size: 18px;
    }
}
</style>
@endpush
@endonce
