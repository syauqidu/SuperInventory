<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - SuperInventory</title>

</head>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="me-2 d-inline-flex align-items-center justify-content-center" style="width:38px;height:38px;background:linear-gradient(135deg,#6366f1,#7c3aed);border-radius:8px;">
                    <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="fw-bold">SuperInventory</span>
            </a>

            <div class="d-flex align-items-center">
                <div class="me-3 text-muted small">
                    Halo, <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                    <span class="badge bg-secondary ms-2">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-4">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <h1 class="h4">Dashboard</h1>
            <p class="text-muted small">Sistem Manajemen Stok Barang UMKM</p>
        </div>

        <div class="card mb-4">
            <div class="card-body text-center">
                <!-- <div class="mb-2">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width:56px;height:56px;">
                        <svg width="28" height="28" fill="none" stroke="#6366f1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <h2 class="h5 mb-1">Dashboard Content</h2>
                <p class="text-muted small">Ringkasan ringkas dan navigasi cepat.</p> -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Produk</h6>
                                <h3 class="fw-bold">{{ $totalProducts }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Supplier</h6>
                                <h3 class="fw-bold">{{ $totalSuppliers }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Total Stok</h6>
                                <h3 class="fw-bold">{{ $totalStock }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-muted">Stok Rendah</h6>
                                <h3 class="fw-bold text-danger">{{ $lowStockCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mb-3 fw-semibold">Aktivitas Terbaru (7 Hari Terakhir)</h6>
                        @if($latestActivities->isEmpty())
                            <p class="text-muted small mb-0">Belum ada aktivitas terbaru.</p>
                        @else
                        <ul class="list-group list-group-flush">
                            @foreach($latestActivities as $log)
                                <li class="list-group-item small d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $log->user->name ?? 'Unknown User' }}</strong> 
                                        {{ strtolower($log->action) }} 
                                        <strong>{{ $log->product->name ?? 'Produk Tidak Ditemukan' }}</strong>
                                    </div>
                                    <span class="text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Manajemen Stok Barang</h5>
                            <p class="card-text small text-muted">CRUD Produk - by Dafa</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('suppliers.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Manajemen Supplier</h5>
                            <p class="card-text small text-muted">CRUD Supplier - by Rafli</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ route('stock-history.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Log/History Stok</h5>
                            <p class="card-text small text-muted">Riwayat Perubahan - by Darryl</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="mt-4 bg-white border rounded p-3">
            <h6 class="mb-3">👥 Tim Pengembang SuperInventory</h6>
            <div class="row g-2">
                <div class="col-6 col-md-4"><div class="p-2 bg-light rounded">Syauqi (Uqi)<div class="small text-muted">Frontend - Register</div></div></div>
                <div class="col-6 col-md-4"><div class="p-2 bg-light rounded">Nijar<div class="small text-muted">Frontend - Login</div></div></div>
                <div class="col-6 col-md-4"><div class="p-2 bg-light rounded">Kalel<div class="small text-muted">Backend - Dashboard</div></div></div>
                <div class="col-6 col-md-4"><div class="p-2 bg-light rounded">Dafa<div class="small text-muted">Fullstack - Stok Barang</div></div></div>
                <div class="col-6 col-md-4"><div class="p-2 bg-light rounded">Rafli<div class="small text-muted">Fullstack - Supplier</div></div></div>
                <div class="col-6 col-md-4"><div class="p-2 bg-light rounded">Darryl<div class="small text-muted">Backend - Log/History</div></div></div>
            </div>
        </div>

    </main>

</body>
</html>
