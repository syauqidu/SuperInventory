@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h4 mb-0">Daftar Supplier</h2>
                            <small class="text-muted">Total: {{ $supplierCount }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="input-group me-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari supplier...">
                                <!--<span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>-->
                            </div>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary text-nowrap">
                                <i class="bi bi-plus-circle me-1"></i>
                                Tambah Supplier
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Kontak</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="supplierTableBody">
                                @forelse ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->contact }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus supplier ini?')">
                                                <i class="bi bi-trash3-fill me-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data supplier.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('supplierTableBody');
        const tableRows = tableBody.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function () {
            const searchTerm = searchInput.value.toLowerCase();

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const rowData = row.textContent.toLowerCase();

                if (rowData.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
</script>
@endpush
