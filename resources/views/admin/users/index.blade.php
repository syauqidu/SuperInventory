@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Manajemen Pengguna</h4>
                <p class="small text-muted mb-0">Approve pendaftaran staff dan kelola user.</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Buat User</a>
            </div>
        </div>

        <div class="mb-3">
            <a href="?filter=pending"
                class="btn btn-sm btn-outline-primary {{ $filter == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="?filter=all" class="btn btn-sm btn-outline-secondary {{ $filter == 'all' ? 'active' : '' }}">Semua</a>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Approved</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ ucfirst($u->role) }}</td>
                                <td>
                                    @if ($u->approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>

                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST"
                                        class="d-inline-block" onsubmit="return confirm('Hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>

                                    @if (!$u->approved)
                                        <form action="{{ route('admin.users.approve', $u) }}" method="POST"
                                            class="d-inline-block">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">Tidak ada user</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
