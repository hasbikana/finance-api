<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FinanceApp - Login')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root { --bs-primary: #0F766E; --bs-secondary: #14B8A6; --bs-accent: #22C55E; }
        body { background: linear-gradient(135deg, #0F766E 0%, #14B8A6 50%, #22C55E 100%); min-height: 100vh; display: flex; align-items: center; font-family: 'Segoe UI', sans-serif; }
        .auth-card { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
        .btn-primary { background-color: #0F766E; border-color: #0F766E; }
        .btn-primary:hover { background-color: #0c5f58; border-color: #0c5f58; }
        .text-primary { color: #0F766E !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card p-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
