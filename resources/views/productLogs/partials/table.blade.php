<table class="table table-hover align-middle" id="LogsTableBody">
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
