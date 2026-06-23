<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1 class="mb-4">Daftar Order</h1>

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
                    <td>{{ Str::limit($order->pickup, 30) }}</td>
                    <td>{{ Str::limit($order->destination, 30) }}</td>
                    <td>{{ $order->vehicle_type }}</td>
                    <td>{{ number_format($order->distance, 2) }} km</td>
                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    <td>
                        @if($order->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status === 'ongoing')
                            <span class="badge bg-info">Ongoing</span>
                        @elseif($order->status === 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($order->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($order->status === 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endif
                    </td>
                    <td>
                        {{-- Edit --}}
                        @if($order->status === 'pending')
                            <a href="{{ route('orders.edit', $order->id) }}"
                               class="btn btn-warning btn-sm">Edit</a>
                        @endif

                        {{-- Delete --}}
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.destroy', $order->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus order ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif

                        {{-- Beri Rating --}}
                        @if($order->status === 'paid' && $order->driver)
                            @php
                                $alreadyRated = $order->ratings->where('rater_id', Auth::id())->where('type', 'driver')->count();
                            @endphp
                            @if(!$alreadyRated)
                                <a href="{{ route('ratings.create', $order->id) }}"
                                   class="btn btn-primary btn-sm">Beri Rating</a>
                            @else
                                <span class="badge bg-secondary">Sudah Dirating</span>
                            @endif
                        @endif
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