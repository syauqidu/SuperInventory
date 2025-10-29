<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'SuperInventory') - Sistem Manajemen Stok UMKM</title>
    
    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <div class="min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5">

            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:64px;height:64px;background:linear-gradient(135deg,#6366f1,#7c3aed);">
                    <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h1 class="h4 fw-bold mb-1">SuperInventory</h1>
                <p class="text-muted small">Sistem Manajemen Stok UMKM</p>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>

            <div class="text-center mt-3 small text-muted">
                <p class="mb-0">&copy; {{ date('Y') }} SuperInventory. Tim DevOps UMKM</p>
                <p class="mb-0">Dibuat oleh Syauqi, Nijar, Kalel, Dafa, Siganteng, Darryl</p>
            </div>

        </div>
    </div>

</body>
</html>
