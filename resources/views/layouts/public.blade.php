<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Settings::get('site_name', 'Digitalisasi Makam'))</title>
    <meta name="description" content="{{ \App\Models\Settings::get('meta_description', '') }}">
    <meta name="keywords" content="{{ \App\Models\Settings::get('meta_keywords', '') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2C3E50;
            --secondary: #34495E;
            --accent: #1ABC9C;
            --gold: #C9A961;
            --cream: #F5F1E8;
            --dark: #1A252F;
            --light: #ECF0F1;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Source Sans 3', sans-serif;
            background: linear-gradient(135deg, var(--cream) 0%, #E8E4DB 100%);
            min-height: 100vh;
            color: var(--dark);
        }
        
        h1, h2, h3, h4, h5, .brand { font-family: 'Playfair Display', serif; }
        
        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gold) !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .nav-link {
            color: var(--light) !important;
            font-weight: 500;
            padding: 0.5rem 1.25rem !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin: 0 0.25rem;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(201, 169, 97, 0.2);
            color: var(--gold) !important;
        }
        
        .navbar-toggler { border-color: var(--gold); }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23C9A961' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Main Content */
        .main-content { padding: 2rem 0; min-height: calc(100vh - 160px); }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        /* Makam Card */
        .makam-card { background: white; border-radius: 16px; overflow: hidden; }
        
        .makam-card .placeholder-img {
            height: 160px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 3rem;
        }
        
        .makam-card .card-img-top { max-height: 280px; width: auto; object-fit: contain; object-position: top; }
        
        .makam-card .nama {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1A252F;
        }
        
        .makam-card .info { font-size: 0.9rem; color: #4A5568; }
        
        .makam-card .lokasi {
            background: var(--cream);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #2D3748;
            margin-top: 0.75rem;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, #16A085 100%);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #16A085 0%, #138D75 100%);
            transform: translateY(-2px);
        }
        
        .btn-outline-primary {
            color: #0d9488;
            border-color: #0d9488;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .btn-outline-primary:hover {
            background: #0d9488;
            border-color: #0d9488;
            color: white;
        }
        
        /* Form */
        .form-control, .form-select {
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.2);
        }
        
        /* Search Box */
        .search-box {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        /* Hero */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .hero h1 { color: var(--gold); margin-bottom: 1rem; }
        .hero p { opacity: 0.9; font-size: 1.1rem; }
        
        /* Stats */
        .stats-widget {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .stats-widget .stats-icon {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }
        
        .stats-widget .stats-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .stats-widget .stats-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        /* Footer */
        footer {
            margin-top: 4rem;
            background: linear-gradient(180deg, #1A252F 0%, #0f1419 100%);
            color: rgba(255,255,255,0.85);
            border-top: 4px solid var(--gold);
        }
        .footer-main {
            padding: 3rem 0 2rem;
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            font-weight: 600;
            color: white;
        }
        .footer-brand img { height: 40px; width: auto; object-fit: contain; }
        .footer-brand .bi-flower1 { font-size: 1.75rem; color: var(--gold); }
        .footer-desc {
            font-size: 0.9rem;
            line-height: 1.6;
            color: rgba(255,255,255,0.7);
            max-width: 280px;
        }
        .footer-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--gold);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            transition: color 0.2s, transform 0.2s;
        }
        .footer-links a:hover { color: var(--gold); transform: translateX(4px); }
        .footer-links a i { font-size: 1rem; opacity: 0.9; }
        .footer-bottom {
            padding: 1rem 0;
            border-top: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }
        .footer-bottom p {
            margin: 0;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
        }
        .footer-bottom a { color: var(--gold); text-decoration: none; }
        .footer-bottom a:hover { text-decoration: underline; }
        footer .border-secondary { border-color: rgba(255,255,255,0.08) !important; }
        .footer-map-layanan .row { align-items: stretch; }
        .footer-map-layanan .footer-block {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .footer-map-wrap,
        .footer-jam-wrap {
            border-radius: 12px;
            overflow: hidden;
            background: rgba(255,255,255,0.05);
            height: 220px;
            flex: 0 0 auto;
        }
        .footer-jam-wrap {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
        }
        .footer-map-wrap iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        .footer-jam-layanan {
            color: rgba(255,255,255,0.85);
            font-size: 0.95rem;
            line-height: 1.7;
        }
        @media (max-width: 768px) {
            .footer-main { padding: 2rem 0 1.5rem; text-align: center; }
            .footer-desc { max-width: none; margin: 0 auto; }
            .footer-brand { justify-content: center; }
            .footer-col { margin-bottom: 1.5rem; }
            .footer-col .footer-title { margin-top: 0.5rem; }
            .footer-map-wrap, .footer-jam-wrap { height: 180px; }
        }
        
        /* Pagination */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination .page-link {
            border: 1px solid #dee2e6;
            color: var(--primary);
            border-radius: 8px;
            margin: 0 2px;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .pagination .page-link:hover {
            background: var(--cream);
            border-color: var(--accent);
            color: var(--accent);
        }
        
        .pagination .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                @if(\App\Models\Settings::get('site_logo'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(\App\Models\Settings::get('site_logo')) }}" alt="Logo" height="40" class="me-2">
                @else
                    <i class="bi bi-flower1"></i>
                @endif
                {{ \App\Models\Settings::get('site_name', 'Digitalisasi Makam') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}" href="{{ route('search') }}">
                            <i class="bi bi-search me-1"></i> Cari Makam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('denah') ? 'active' : '' }}" href="{{ route('denah') }}">
                            <i class="bi bi-map me-1"></i> Denah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('keuangan.public') ? 'active' : '' }}" href="{{ route('keuangan.public') }}">
                            <i class="bi bi-wallet2 me-1"></i> Laporan Keuangan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('hero')

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer: 1 baris saja -->
    @php
        $footerMapUrl = \App\Models\Settings::get('map_embed_url', '');
        $footerKontak = \App\Models\KontakAdmin::getKontak();
    @endphp
    <footer>
        <div class="container py-4">
            <div class="row g-4 align-items-start">
                <div class="col-lg-3 footer-col">
                    <div class="footer-brand">
                        @if(\App\Models\Settings::get('site_logo'))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(\App\Models\Settings::get('site_logo')) }}" alt="{{ \App\Models\Settings::get('site_name', 'Logo') }}">
                        @else
                            <i class="bi bi-flower1"></i>
                        @endif
                        <span>{{ \App\Models\Settings::get('site_name', 'Digitalisasi Makam') }}</span>
                    </div>
                    <p class="footer-desc">{{ \App\Models\Settings::get('site_description', 'Sistem informasi pengelolaan data makam yang modern dan terorganisir.') }}</p>
                </div>
                <div class="col-lg-2 footer-col">
                    <div class="footer-title">Tautan Cepat</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="bi bi-house-door"></i> Beranda</a></li>
                        <li><a href="{{ route('search') }}"><i class="bi bi-search"></i> Cari Makam</a></li>
                        <li><a href="{{ route('denah') }}"><i class="bi bi-map"></i> Denah</a></li>
                        <li><a href="{{ route('keuangan.public') }}"><i class="bi bi-wallet2"></i> Laporan Keuangan</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 footer-block">
                    <div class="footer-title"><i class="bi bi-geo-alt me-1"></i> Peta Lokasi</div>
                    <div class="footer-map-wrap">
                        @if($footerMapUrl)
                            <iframe src="{{ $footerMapUrl }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi"></iframe>
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="color: rgba(255,255,255,0.4);">
                                <span class="small"><i class="bi bi-map me-1"></i> URL peta diatur di Pengaturan Website</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 footer-block">
                    <div class="footer-title"><i class="bi bi-clock me-1"></i> Jam Layanan</div>
                    <div class="footer-jam-wrap">
                        @if($footerKontak->jam_layanan)
                            <div class="footer-jam-layanan">{!! nl2br(e($footerKontak->jam_layanan)) !!}</div>
                        @else
                            <p class="small mb-0 footer-jam-layanan" style="color: rgba(255,255,255,0.5);">Jam layanan diatur di Kontak Admin.</p>
                        @endif
                    </div>
                </div>
                <div class="col-12 pt-3 mt-2 border-top border-secondary text-center">
                    <p class="mb-0" style="font-size: 0.85rem; color: rgba(255,255,255,0.5);">{!! \App\Models\Settings::get('footer_text', '&copy; ' . date('Y') . ' ' . \App\Models\Settings::get('site_name', 'Digitalisasi Makam') . '. All rights reserved.') !!}</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
