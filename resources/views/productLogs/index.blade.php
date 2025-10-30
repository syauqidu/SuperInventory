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
    <style>
        table.table th,
        table.table td {
            padding: 0.9rem 1rem !important;
            vertical-align: middle;
        }

        table.table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8f9fa;
        }
    </style>


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

        {{-- Hero Section --}}
        <div class="mb-2">
            <a href="{{ url()->previous() }}" class="btn btn-light align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" style="width:20px;height:20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="ms-1 me-2">Back</span>
            </a>
        </div>
        <div class="d-flex align-items-center mb-4">
            <div class="me-2 d-inline-flex align-items-center justify-content-center bg-light rounded-circle"
                style="width:42px;height:42px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="#6366f1" style="width:24px;height:24px;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                </svg>
            </div>
            <div>
                <h1 class="h5 mb-0">Logs / History</h1>
                <p class="text-muted small mb-0">Riwayat Stok Barang UMKM</p>
            </div>
        </div>

        {{-- table filter/helper --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <form method="GET" action="{{ route('stock-history.index') }}"
                class="d-flex align-items-center flex-wrap gap-2 w-100">
                <!-- Search bar -->
                <input type="text" name="search" class="form-control" placeholder="Search user or product..."
                    value="{{ request('search') }}" style="max-width: 300px; height: 40px;">

                <div class="ms-auto d-flex align-items-center gap-2 flex-wrap">
                    <!-- Action filter -->
                    <select name="action" class="form-select" style="height: 40px; width: 140px;">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>

                    <!-- Buttons -->
                    <button type="submit" class="btn btn-primary" style="height: 40px;">Filter</button>
                    <a href="{{ route('stock-history.index') }}" class="btn btn-light border"
                        style="height: 40px;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                                    <td class="text-center">
                                        @php
                                            $badgeClass = match ($log->action) {
                                                'created' => 'bg-success',
                                                'updated' => 'bg-warning text-dark',
                                                'deleted' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }} text-capitalize">
                                            {{ $log->action }}
                                        </span>
                                    </td>

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
