<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Digitalisasi Makam')</title>
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Source Sans 3', sans-serif;
            background: linear-gradient(135deg, var(--cream) 0%, #E8E4DB 100%);
            min-height: 100vh;
            color: var(--dark);
        }
        
        h1, h2, h3, h4, h5, .brand {
            font-family: 'Playfair Display', serif;
        }
        
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
        
        .navbar-brand i {
            font-size: 1.75rem;
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
        
        .navbar-toggler {
            border-color: var(--gold);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23C9A961' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 80px);
        }
        
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
        
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: 3px solid var(--gold);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, #16A085 100%);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #16A085 0%, #138D75 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 188, 156, 0.4);
        }
        
        .btn-secondary {
            background: var(--secondary);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
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
        
        .btn-warning {
            background: var(--gold);
            border: none;
            color: var(--dark);
            border-radius: 25px;
        }
        
        .btn-danger {
            border-radius: 25px;
        }
        
        /* Form Controls */
        .form-control, .form-select {
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.2);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        
        /* Makam Card */
        .makam-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .makam-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        .makam-card .card-img-top {
            height: 160px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .makam-card .placeholder-img {
            height: 160px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 3rem;
        }
        
        .makam-card .card-body {
            padding: 1.25rem;
        }
        
        .makam-card .nama {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1A252F;
            margin-bottom: 0.5rem;
        }
        
        .makam-card .info {
            font-size: 0.9rem;
            color: #4A5568;
        }
        
        .makam-card .lokasi {
            background: var(--cream);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--secondary);
            margin-top: 0.75rem;
        }
        
        /* Blok Card */
        .blok-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border-left: 4px solid var(--gold);
        }
        
        .blok-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .blok-card .blok-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--gold);
            font-size: 1.75rem;
        }
        
        .blok-card .blok-nama {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .blok-card .blok-stats {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .blok-card .stat-item {
            text-align: center;
        }
        
        .blok-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
        }
        
        .blok-card .stat-label {
            font-size: 0.8rem;
            color: #888;
        }
        
        /* Alert */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #D4EDDA 0%, #C3E6CB 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #F8D7DA 0%, #F5C6CB 100%);
            color: #721C24;
        }
        
        /* Pagination */
        .pagination .page-link {
            border: none;
            color: var(--secondary);
            border-radius: 8px;
            margin: 0 2px;
        }
        
        .pagination .page-item.active .page-link {
            background: var(--accent);
        }
        
        /* Search Box */
        .search-box {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        /* Stats Widget */
        .stats-widget {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            text-align: center;
        }
        
        .stats-widget .stats-icon {
            font-size: 2.5rem;
            color: var(--gold);
            margin-bottom: 0.5rem;
        }
        
        .stats-widget .stats-value {
            font-size: 2rem;
            font-weight: 700;
        }
        
        .stats-widget .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Detail Page */
        .detail-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: -3rem;
            border-radius: 0 0 30px 30px;
        }
        
        .detail-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 40px rgba(0,0,0,0.1);
        }
        
        .detail-label {
            font-size: 0.85rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        /* Denah */
        .denah-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
        }
        
        .denah-blok {
            background: linear-gradient(135deg, var(--cream) 0%, #E8E4DB 100%);
            border: 2px solid var(--gold);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .denah-blok::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 20px;
            background: var(--gold);
            padding: 0.25rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .makam-slot {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .makam-slot.terisi {
            background: var(--primary);
            color: white;
        }
        
        .makam-slot.kosong {
            background: #E0E0E0;
            color: #999;
        }
        
        .makam-slot:hover {
            transform: scale(1.1);
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: var(--light);
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        footer a {
            color: var(--gold);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .stats-widget {
                margin-bottom: 1rem;
            }
            
            .makam-card .placeholder-img {
                height: 120px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-flower1"></i>
                Digitalisasi Makam
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
                        <a class="nav-link {{ request()->routeIs('makam.*') ? 'active' : '' }}" href="{{ route('makam.index') }}">
                            <i class="bi bi-archive me-1"></i> Data Makam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blok.*') ? 'active' : '' }}" href="{{ route('blok.index') }}">
                            <i class="bi bi-grid-3x3 me-1"></i> Blok Makam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('denah') ? 'active' : '' }}" href="{{ route('denah') }}">
                            <i class="bi bi-map me-1"></i> Denah
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Sistem Digitalisasi Makam. Dibuat dengan <i class="bi bi-heart-fill text-danger"></i></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
