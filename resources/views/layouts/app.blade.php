<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ $hospitalName ?? 'Hospital HMS' }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --sidebar-bg: #1a2035;
            --sidebar-hover: #252d48;
            --sidebar-active: #0d6efd;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #333;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

        .sidebar-brand {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: 10px;
        }

        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 18px; flex-shrink: 0;
        }

        .sidebar-brand .brand-text { color: #fff; font-weight: 700; font-size: 15px; line-height: 1.2; }
        .sidebar-brand .brand-sub  { color: rgba(255,255,255,0.5); font-size: 11px; }

        .sidebar-section {
            padding: 16px 20px 6px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.35);
            font-weight: 600;
        }

        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13.5px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav a.active {
            background: rgba(13,110,253,0.15);
            color: #fff;
            border-left-color: var(--primary);
            font-weight: 500;
        }

        .sidebar-nav a i {
            width: 20px; text-align: center;
            font-size: 14px; opacity: 0.8;
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0; left: var(--sidebar-width); right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            z-index: 999;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .page-title { font-size: 16px; font-weight: 600; color: #1a2035; margin: 0; }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }

        .content-body { padding: 24px; }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f2f5;
            border-radius: 12px 12px 0 0 !important;
            padding: 16px 20px;
            font-weight: 600;
            font-size: 14px;
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card .stat-icon {
            position: absolute; right: 16px; top: 16px;
            font-size: 2.5rem; opacity: 0.25;
        }

        .stat-card .stat-number { font-size: 2rem; font-weight: 700; margin: 0; }
        .stat-card .stat-label  { font-size: 12px; opacity: 0.85; margin-top: 4px; }

        .stat-bg-primary   { background: linear-gradient(135deg, #0d6efd, #6610f2); }
        .stat-bg-success   { background: linear-gradient(135deg, #198754, #20c997); }
        .stat-bg-warning   { background: linear-gradient(135deg, #fd7e14, #ffc107); }
        .stat-bg-danger    { background: linear-gradient(135deg, #dc3545, #e91e63); }
        .stat-bg-info      { background: linear-gradient(135deg, #0dcaf0, #0d6efd); }
        .stat-bg-purple    { background: linear-gradient(135deg, #6f42c1, #d63384); }
        .stat-bg-teal      { background: linear-gradient(135deg, #20c997, #0dcaf0); }
        .stat-bg-orange    { background: linear-gradient(135deg, #fd7e14, #dc3545); }

        /* Badges */
        .badge { font-size: 11px; padding: 4px 8px; border-radius: 6px; }

        /* Tables */
        .table { font-size: 13.5px; }
        .table thead th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 16px;
        }
        .table td { padding: 12px 16px; vertical-align: middle; }
        .table tbody tr:hover { background: #f8f9fa; }

        /* Buttons */
        .btn { border-radius: 8px; font-size: 13px; font-weight: 500; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }

        /* Forms */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #dee2e6;
            font-size: 13.5px;
            padding: 8px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        }
        .form-label { font-size: 13px; font-weight: 500; color: #495057; margin-bottom: 5px; }

        /* Avatar */
        .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600; color: #fff;
        }

        .avatar-sm { width: 28px; height: 28px; font-size: 11px; }
        .avatar-lg { width: 52px; height: 52px; font-size: 18px; }

        /* Alert */
        .alert { border: none; border-radius: 10px; font-size: 13.5px; }

        /* Breadcrumb */
        .breadcrumb { font-size: 13px; }
        .breadcrumb-item.active { color: #6c757d; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .topbar, .main-content { left: 0; margin-left: 0; }
        }

        /* Print */
        @media print {
            .sidebar, .topbar, .no-print { display: none !important; }
            .main-content { margin: 0 !important; padding: 0 !important; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-hospital-alt"></i></div>
        <div>
            <div class="brand-text">{{ $hospitalName ?? 'Hospital HMS' }}</div>
            <div class="brand-sub">Management System</div>
        </div>
    </div>

    <div class="sidebar-nav mt-2">
        @include('partials.sidebar-menu')
    </div>
</nav>

{{-- Topbar --}}
<header class="topbar">
    <div class="topbar-left">
        <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
    </div>
    <div class="topbar-right">
        {{-- Notifications --}}
        <div class="dropdown">
            <button class="btn btn-sm btn-light position-relative" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px">3</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="min-width:280px">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <li><a class="dropdown-item small" href="#"><i class="fas fa-calendar-check text-primary me-2"></i>3 appointments today</a></li>
                <li><a class="dropdown-item small" href="#"><i class="fas fa-pills text-warning me-2"></i>Low stock: Tramadol 50mg</a></li>
                <li><a class="dropdown-item small" href="#"><i class="fas fa-flask text-info me-2"></i>2 lab results ready</a></li>
            </ul>
        </div>

        {{-- User dropdown --}}
        <div class="dropdown">
            <button class="btn btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div class="avatar avatar-sm" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start" style="line-height:1.2">
                    <div style="font-size:13px; font-weight:600">{{ auth()->user()->name }}</div>
                    <div style="font-size:11px; color:#6c757d">{{ auth()->user()->role_label }}</div>
                </div>
                <i class="fas fa-chevron-down" style="font-size:10px; color:#6c757d"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                <li><a class="dropdown-item small" href="{{ route('password.change') }}"><i class="fas fa-key me-2"></i>Change Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item small text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

{{-- Main Content --}}
<main class="main-content">
    <div class="content-body">

        {{-- Breadcrumb --}}
        @hasSection('breadcrumb')
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                @yield('breadcrumb')
            </ol>
        </nav>
        @endif

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the errors below:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</main>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert?.close();
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
