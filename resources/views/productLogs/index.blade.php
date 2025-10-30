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
                <div class="me-2 d-inline-flex align-items-center justify-content-center"
                    style="width:38px;height:38px;background:linear-gradient(135deg,#6366f1,#7c3aed);border-radius:8px;">
                    <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
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

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-4">
            <h1 class="h4">Logs/History</h1>
            <p class="text-muted small">History Stok Barang UMKM</p>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                {{-- <div class="text-center mb-4">
                    <div class="mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle"
                            style="width:56px;height:56px;">
                            <svg width="28" height="28" fill="none" stroke="#6366f1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="h5 mb-1">Product Logs</h2>
                    <p class="text-muted small">Riwayat aktivitas pengguna terhadap produk.</p>
                </div> --}}

                <!-- Table Section -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>User</th>
                                <th>Product</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                                    <td>{{ $log->product->name ?? 'N/A' }}</td>
                                    <td class="text-capitalize text-center">{{ $log->action }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td class="text-muted small">{{ $log->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada aktivitas produk
                                        tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </main>

</body>

</html>
