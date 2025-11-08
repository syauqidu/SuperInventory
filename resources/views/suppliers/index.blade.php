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
                            </div>
                            <button type="button" class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                <i class="bi bi-plus-circle me-1"></i>
                                Tambah Supplier
                            </button>
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
                                        <button type="button" class="btn btn-outline-warning btn-sm edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSupplierModal"
                                            data-id="{{ $supplier->id }}"
                                            data-name="{{ $supplier->name }}"
                                            data-contact="{{ $supplier->contact }}"
                                            data-address="{{ $supplier->address }}"
                                            data-update-url="{{ route('suppliers.update', $supplier->id) }}">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Edit
                                        </button>
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

@include('suppliers.partials.modals')

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

        const editSupplierModal = document.getElementById('editSupplierModal');
        editSupplierModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const name = button.getAttribute('data-name');
            const contact = button.getAttribute('data-contact');
            const address = button.getAttribute('data-address');
            const updateUrl = button.getAttribute('data-update-url');

            const modalForm = document.getElementById('editSupplierForm');
            modalForm.action = updateUrl;

            const modalNameInput = document.getElementById('edit_name');
            const modalContactInput = document.getElementById('edit_contact');
            const modalAddressInput = document.getElementById('edit_address');

            modalNameInput.value = name;
            modalContactInput.value = contact;
            modalAddressInput.value = address;
        });

        @if ($errors->any())
            var addModal = new bootstrap.Modal(document.getElementById('addSupplierModal'));
            addModal.show();
        @endif
    });
</script>
@endpush
