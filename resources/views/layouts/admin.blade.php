<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        * { box-sizing: border-box; }

        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ── HEADER ─────────────────────────────────── */
        .admin-header {
            background: linear-gradient(135deg, #003580, #0056b3);
            color: white;
            padding: 0 16px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1100;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }

        .admin-header .brand {
            font-size: 16px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-header .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-header .admin-badge {
            background: rgba(255,255,255,.15);
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 12px;
        }

        .btn-sidebar-toggle {
            display: none;
            background: rgba(255,255,255,.15);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            flex-shrink: 0;
        }

        /* ── SIDEBAR ─────────────────────────────────── */
        .admin-sidebar {
            position: fixed;
            top: 56px; left: 0;
            width: 240px;
            height: calc(100vh - 56px);
            background: white;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            z-index: 1050;
            box-shadow: 2px 0 8px rgba(0,0,0,.05);
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: .8px;
            padding: 16px 20px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: #495057;
            text-decoration: none;
            font-size: 14px;
            transition: all .2s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background: #f0f4ff;
            color: #003580;
            border-left-color: #003580;
        }

        .sidebar-link.active {
            background: #e8efff;
            color: #003580;
            font-weight: 600;
            border-left-color: #003580;
        }

        .sidebar-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* ── OVERLAY ─────────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1040;
        }

        .sidebar-overlay.active { display: block; }

        /* ── MAIN CONTENT ────────────────────────────── */
        .admin-content {
            margin-left: 240px;
            margin-top: 56px;
            min-height: calc(100vh - 56px);
            padding: 24px;
        }

        /* ── CARDS ───────────────────────────────────── */
        .admin-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            border: 1px solid #e9ecef;
        }

        .admin-card-body { padding: 20px; }

        /* ── ALERTS ──────────────────────────────────── */
        .alert { border-radius: 8px; }

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 991px) {
            .btn-sidebar-toggle { display: flex; }

            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0,0,0,.2);
            }

            .admin-content {
                margin-left: 0;
                padding: 16px;
            }
        }

        @media (max-width: 575px) {
            .admin-content { padding: 12px; }

            .admin-header .admin-badge { display: none; }
        }
    </style>
</head>
<body>

<!-- Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Header -->
<header class="admin-header">
    <div class="d-flex align-items-center gap-2">
        <button class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Menu">
            <i class="bi bi-list"></i>
        </button>
        <a href="{{ route('admin.locations.index') }}" class="brand">
            <span style="font-size:20px;">🗺️</span>
            <span class="d-none d-sm-inline">Quản trị Bản đồ số</span>
        </a>
    </div>
    <div class="header-right">
        <span class="admin-badge">
            <i class="bi bi-person-circle"></i> {{ session('admin_username', 'Admin') }}
        </span>
        <a href="{{ route('home') }}" class="text-white text-decoration-none" style="font-size:13px;" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i>
            <span class="d-none d-md-inline">Xem trang</span>
        </a>
    </div>
</header>

<!-- Sidebar -->
<nav class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-section-title">Tổng quan</div>
    <a href="{{ route('admin.locations.index') }}" class="sidebar-link {{ request()->routeIs('admin.locations.index') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-section-title">Quản lý nội dung</div>
    <a href="{{ route('admin.locations.index') }}" class="sidebar-link {{ request()->routeIs('admin.locations.index') || request()->routeIs('admin.locations.edit') ? 'active' : '' }}">
        <i class="bi bi-geo-alt"></i> Địa điểm
    </a>
    <a href="{{ route('admin.locations.create') }}" class="sidebar-link {{ request()->routeIs('admin.locations.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i> Thêm địa điểm
    </a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i> Danh mục
    </a>

    <div class="sidebar-section-title">Hệ thống</div>
    <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
        <i class="bi bi-house"></i> Trang chủ
    </a>
    <a href="{{ route('map') }}" class="sidebar-link" target="_blank">
        <i class="bi bi-map"></i> Bản đồ
    </a>
    <div class="px-3 py-2">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </button>
        </form>
    </div>
</nav>

<!-- Main -->
<main class="admin-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const toggle  = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function open()  { sidebar.classList.add('open');    overlay.classList.add('active'); }
        function close() { sidebar.classList.remove('open'); overlay.classList.remove('active'); }

        toggle.addEventListener('click', () => sidebar.classList.contains('open') ? close() : open());
        overlay.addEventListener('click', close);

        // Close sidebar when a nav link is clicked on mobile
        sidebar.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => { if (window.innerWidth < 992) close(); });
        });
    })();
</script>
@stack('scripts')
</body>
</html>
