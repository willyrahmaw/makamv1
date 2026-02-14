<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Digitalisasi Makam</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --admin-primary: #4F46E5;
            --admin-secondary: #6366F1;
            --admin-dark: #1E1B4B;
            --admin-light: #F5F3FF;
            --admin-success: #10B981;
            --admin-danger: #EF4444;
            --admin-warning: #F59E0B;
            --sidebar-width: 260px;
        }
        
        * { box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F1F5F9;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--admin-dark) 0%, #312E81 100%);
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand-logo {
            max-width: 120px;
            max-height: 60px;
            margin: 0 auto 0.75rem;
            display: block;
            object-fit: contain;
        }
        
        .sidebar-brand h4 {
            margin: 0;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .sidebar-brand small {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            margin-top: 1rem;
        }
        
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--admin-secondary);
        }
        
        .sidebar-nav .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Topbar */
        .topbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .topbar h5 {
            margin: 0;
            font-weight: 600;
            color: var(--admin-dark);
        }
        
        .topbar .admin-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .topbar .admin-avatar {
            width: 40px;
            height: 40px;
            background: var(--admin-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Content Area */
        .content-area {
            padding: 2rem;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #E2E8F0;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.primary { background: #EEF2FF; color: var(--admin-primary); }
        .stat-icon.success { background: #D1FAE5; color: var(--admin-success); }
        .stat-icon.warning { background: #FEF3C7; color: var(--admin-warning); }
        .stat-icon.danger { background: #FEE2E2; color: var(--admin-danger); }
        
        .stat-info h3 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--admin-dark);
        }
        
        .stat-info p {
            margin: 0;
            color: #64748B;
            font-size: 0.875rem;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--admin-primary);
            border: none;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: var(--admin-secondary);
        }
        
        /* Table */
        .table th {
            font-weight: 600;
            color: #475569;
            border-bottom-width: 1px;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        /* Form */
        .form-label {
            font-weight: 500;
            color: #374151;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #E2E8F0;
            padding: 0.6rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        /* Alert */
        .alert {
            border: none;
            border-radius: 8px;
        }
        
        /* Badge */
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
        }
        
        /* Overlay saat sidebar terbuka (mobile) */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
        
        /* Tombol menu mobile */
        .topbar .btn-sidebar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            padding: 0;
            border: none;
            border-radius: 10px;
            background: #EEF2FF;
            color: var(--admin-primary);
            font-size: 1.35rem;
        }
        .topbar .btn-sidebar-toggle:hover {
            background: #E0E7FF;
            color: var(--admin-secondary);
        }
        
        /* Responsive mobile */
        @media (max-width: 992px) {
            .sidebar {
                width: min(280px, 85vw);
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0,0,0,0.2);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .topbar .btn-sidebar-toggle {
                display: flex;
            }
            .topbar {
                padding: 0.75rem 1rem;
                flex-wrap: nowrap;
                gap: 0.5rem;
            }
            .topbar h5 {
                font-size: 1rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: calc(100vw - 140px);
            }
            .content-area {
                padding: 1rem;
            }
            /* Touch-friendly nav links (min 44px) */
            .sidebar-nav .nav-link {
                padding: 1rem 1.5rem;
                min-height: 48px;
                align-items: center;
            }
            .sidebar-nav .nav-section {
                margin-top: 0.75rem;
            }
            .sidebar-brand {
                padding: 1.25rem 1rem;
            }
        }
        @media (max-width: 576px) {
            .topbar {
                padding: 0.6rem 0.75rem;
            }
            .topbar h5 {
                font-size: 0.95rem;
                max-width: calc(100vw - 120px);
            }
            .topbar .admin-info span.text-muted {
                display: none;
            }
            .topbar .admin-avatar {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
            .content-area {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar" aria-label="Menu navigasi">
        <div class="sidebar-brand">
            @if(\App\Models\Settings::get('site_logo'))
                <img src="{{ \Illuminate\Support\Facades\Storage::url(\App\Models\Settings::get('site_logo')) }}" 
                     alt="{{ \App\Models\Settings::get('site_name', 'Logo') }}" 
                     class="sidebar-brand-logo">
            @endif
            <h4>
                @if(!\App\Models\Settings::get('site_logo'))
                    <i class="bi bi-flower1"></i>
                @endif
                {{ \App\Models\Settings::get('site_name', 'Makam') }}
            </h4>
            <small>Panel Administrator</small>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            
            <div class="nav-section">Data Master</div>
            <a href="{{ route('admin.makam.index') }}" class="nav-link {{ request()->routeIs('admin.makam.*') ? 'active' : '' }}">
                <i class="bi bi-archive-fill"></i> Data Makam
            </a>
            <a href="{{ route('admin.blok.index') }}" class="nav-link {{ request()->routeIs('admin.blok.*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap-fill"></i> Blok Makam
            </a>
            <a href="{{ route('admin.sejarah.index') }}" class="nav-link {{ request()->routeIs('admin.sejarah.*') ? 'active' : '' }}">
                <i class="bi bi-book-fill"></i> Sejarah Desa
            </a>

            <div class="nav-section">Keuangan</div>
            <a href="{{ route('admin.keuangan.index') }}" class="nav-link {{ request()->routeIs('admin.keuangan.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Laporan Keuangan
            </a>
            
            @if(Auth::guard('admin')->user()?->isSuperAdmin())
            <div class="nav-section">Pengaturan</div>
            <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Pengaturan Website
            </a>
            @endif
            @if(Auth::guard('admin')->user()?->isSuperAdmin())
                <a href="{{ route('admin.kontak.edit') }}" class="nav-link {{ request()->routeIs('admin.kontak.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i> Kontak Admin
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Manajemen Admin
                </a>
            @endif
            <a href="{{ route('admin.logs.index') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <i class="bi bi-activity"></i> Log Aktivitas
            </a>
            
            <div class="nav-section">Lainnya</div>
            <a href="{{ route('admin.password.edit') }}" class="nav-link {{ request()->routeIs('admin.password.*') ? 'active' : '' }}">
                <i class="bi bi-key-fill"></i> Ganti Password
            </a>
            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="bi bi-globe"></i> Lihat Website
            </a>
            <button type="button" class="nav-link w-100 text-start border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn-sidebar-toggle" id="btnSidebarToggle" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="admin-info">
                <span class="text-muted">{{ Auth::guard('admin')->user()->name }}</span>
                <div class="admin-avatar">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
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
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="bi bi-box-arrow-left text-primary me-2"></i>Konfirmasi Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="mb-0">Yakin ingin keluar dari akun?</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline" id="logoutForm">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-left me-1"></i> Ya, Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="mb-0" id="confirmDeleteMessage">Yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            var sidebar = document.getElementById('adminSidebar');
            var overlay = document.getElementById('sidebarOverlay');
            var btn = document.getElementById('btnSidebarToggle');
            if (!sidebar || !overlay || !btn) return;
            function openMenu() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                overlay.setAttribute('aria-hidden', 'false');
                btn.setAttribute('aria-label', 'Tutup menu');
                btn.querySelector('i').className = 'bi bi-x-lg';
                document.body.style.overflow = 'hidden';
            }
            function closeMenu() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                overlay.setAttribute('aria-hidden', 'true');
                btn.setAttribute('aria-label', 'Buka menu');
                btn.querySelector('i').className = 'bi bi-list';
                document.body.style.overflow = '';
            }
            function toggleMenu() {
                sidebar.classList.contains('show') ? closeMenu() : openMenu();
            }
            btn.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', closeMenu);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) closeMenu();
            });
            sidebar.querySelectorAll('.sidebar-nav a, .sidebar-nav button').forEach(function(el) {
                el.addEventListener('click', function() {
                    if (window.innerWidth <= 992) closeMenu();
                });
            });
        })();

        (function() {
            var modal = document.getElementById('confirmDeleteModal');
            var messageEl = document.getElementById('confirmDeleteMessage');
            var confirmBtn = document.getElementById('confirmDeleteBtn');
            var formToSubmit = null;
            if (!modal || !confirmBtn) return;
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('.btn-delete-confirm');
                if (!btn) return;
                e.preventDefault();
                formToSubmit = btn.closest('form');
                if (!formToSubmit) return;
                var msg = btn.getAttribute('data-message') || 'Yakin ingin menghapus data ini?';
                messageEl.textContent = msg;
                formToSubmit = formToSubmit;
                var bsModal = bootstrap.Modal.getOrCreateInstance(modal);
                bsModal.show();
            });
            confirmBtn.addEventListener('click', function() {
                if (formToSubmit) {
                    formToSubmit.submit();
                    formToSubmit = null;
                }
                bootstrap.Modal.getInstance(modal).hide();
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
