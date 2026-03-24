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
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-header {
            background: linear-gradient(135deg, #003580, #0056b3);
            color: white;
            padding: 0 20px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .admin-header .brand {
            font-size: 17px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-header .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-header .admin-badge {
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 13px;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 240px;
            height: calc(100vh - 60px);
            background: white;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            z-index: 999;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 0.8px;
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
            transition: all 0.2s;
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

        /* Main Content */
        .admin-content {
            margin-left: 240px;
            margin-top: 60px;
            min-height: calc(100vh - 60px);
            padding: 24px;
        }

        /* Page header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #dee2e6;
        }

        .page-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }

        /* Alert styles */
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<!-- Top Header -->
<header class="admin-header">
    <a href="{{ route('admin.locations.index') }}" class="brand">
        <span style="font-size:22px;">🗺️</span>
        <span>Quản trị Bản đồ số</span>
    </a>
    <div class="header-right">
        <span class="admin-badge"><i class="bi bi-person-circle"></i> {{ session('admin_username', 'Admin') }}</span>
        <a href="{{ route('home') }}" class="text-white text-decoration-none" style="font-size:13px;" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Xem trang chủ
        </a>
    </div>
</header>

<!-- Sidebar -->
<nav class="admin-sidebar">
    <div class="sidebar-section-title">Tổng quan</div>
    <a href="{{ route('admin.locations.index') }}" class="sidebar-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
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
    <form action="{{ route('admin.logout') }}" method="POST" class="px-3 py-2">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </button>
    </form>
</nav>

<!-- Main Content -->
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
@stack('scripts')
</body>
</html>
