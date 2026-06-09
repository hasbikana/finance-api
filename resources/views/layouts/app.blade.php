<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FinanceApp')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.0.3/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --bs-primary: #0F766E;
            --bs-primary-rgb: 15, 118, 110;
            --bs-secondary: #14B8A6;
            --bs-accent: #22C55E;
            --bs-sidebar-width: 260px;
        }
        .bg-primary { background-color: #0F766E !important; }
        .bg-secondary { background-color: #14B8A6 !important; }
        .bg-accent { background-color: #22C55E !important; }
        .text-primary { color: #0F766E !important; }
        .text-secondary { color: #14B8A6 !important; }
        .text-accent { color: #22C55E !important; }
        .btn-primary { background-color: #0F766E; border-color: #0F766E; }
        .btn-primary:hover { background-color: #0c5f58; border-color: #0c5f58; }
        .btn-secondary { background-color: #14B8A6; border-color: #14B8A6; }
        .btn-secondary:hover { background-color: #109a8a; border-color: #109a8a; }
        .btn-accent { background-color: #22C55E; border-color: #22C55E; color: #fff; }
        .btn-accent:hover { background-color: #1ca14d; border-color: #1ca14d; color: #fff; }
        .btn-outline-primary { color: #0F766E; border-color: #0F766E; }
        .btn-outline-primary:hover { background-color: #0F766E; border-color: #0F766E; }
        .text-bg-primary { background-color: #0F766E !important; }
        .badge.bg-primary { background-color: #0F766E !important; }
        .nav-pills .nav-link.active { background-color: #0F766E; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f8f9fa; }
        .sidebar { width: var(--bs-sidebar-width); position: fixed; top: 0; left: 0; height: 100vh; background: linear-gradient(135deg, #0F766E 0%, #14B8A6 100%); z-index: 1000; overflow-y: auto; }
        .sidebar .nav-link { color: rgba(255,255,255,.8); padding: 0.6rem 1.25rem; border-radius: 8px; margin: 2px 0.5rem; font-size: 0.9rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        .sidebar .nav-link i { margin-right: 10px; font-size: 1.1rem; }
        .main-content { margin-left: var(--bs-sidebar-width); min-height: 100vh; }
        .navbar-top { background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card-stat { border-radius: 12px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,.04); transition: transform .2s; }
        .card-stat:hover { transform: translateY(-2px); }
        .card-stat .icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .progress-thin { height: 8px; border-radius: 4px; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform .3s; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.partials.sidebar')
    <div class="main-content">
        @include('layouts.partials.navbar')
        <div class="container-fluid p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive@2.0.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.0.3/js/responsive.bootstrap5.min.js"></script>
    @stack('scripts')
    <script>
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0F766E',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) { form.submit(); }
                });
            });
        });
    </script>
</body>
</html>
