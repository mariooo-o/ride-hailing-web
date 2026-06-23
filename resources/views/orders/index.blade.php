@extends('layouts.main')

@section('title', 'Order Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Order Saya</h4>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Order
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-borderless table-hover mb-0">
            <thead class="border-bottom">
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
                    <td>#{{ $order->id }}</td>
                    <td>{{ Str::limit($order->pickup, 30) }}</td>
                    <td>{{ Str::limit($order->destination, 30) }}</td>
                    <td>{{ $order->vehicle_type }}</td>
                    <td>{{ number_format($order->distance, 2) }} km</td>
                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    <td>
                        @if($order->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($order->status === 'ongoing')
                            <span class="badge bg-info text-dark">Ongoing</span>
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
                    <td class="d-flex gap-1 flex-wrap">
                        @if($order->status === 'pending')
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus order ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif

                        @if($order->status === 'paid' && $order->driver)
                            @php
                                $alreadyRated = $order->ratings->where('rater_id', Auth::id())->where('type', 'driver')->count();
                            @endphp
                            @if(!$alreadyRated)
                                <a href="{{ route('ratings.create', $order->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-star"></i> Rating
                                </a>
                            @else
                                <span class="badge bg-secondary">Sudah Dirating</span>
                            @endif
                        @endif

                        @if($order->driver)
                            <a href="{{ route('chat.show', $order->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-chat-dots"></i> Chat
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada order.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
