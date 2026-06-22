<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1 class="mb-4">Daftar Order Ride Hailing</h1>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">
        + Tambah Order
    </a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Pickup</th>
                <th>Destination</th>
                <th>Kendaraan</th>
                <th>Jarak</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->pickup }}</td>
                    <td>{{ $order->destination }}</td>
                    <td>{{ $order->vehicle_type }}</td>
                    <td>{{ number_format($order->distance, 2) }} km</td>
                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    <td>
                        @if($order->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </td>
                    <td>
                        {{-- Edit --}}
                        <a href="{{ route('orders.edit', $order->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        {{-- Complete (hanya jika masih pending) --}}
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.complete', $order->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    Complete
                                </button>
                            </form>
                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('orders.destroy', $order->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin hapus order ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Belum ada order.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>