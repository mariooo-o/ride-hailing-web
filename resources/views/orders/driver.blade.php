@extends('layouts.main')

@section('title', 'Order Driver')

@section('content')
<h4 class="fw-bold mb-4">Order Tersedia</h4>

{{-- Order Pending --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Order Pending</h6>
        @if($pendingOrders->isEmpty())
            <p class="text-muted mb-0">Tidak ada order pending saat ini.</p>
        @else
            <table class="table table-borderless table-hover mb-0">
                <thead class="border-bottom">
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Pickup</th>
                        <th>Tujuan</th>
                        <th>Jarak</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingOrders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>{{ Str::limit($order->pickup, 25) }}</td>
                        <td>{{ Str::limit($order->destination, 25) }}</td>
                        <td>{{ number_format($order->distance, 2) }} km</td>
                        <td>{{ $order->vehicle_type }}</td>
                        <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                        <td>
                            <form method="POST" action="{{ route('driver.take-order', $order->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Ambil</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Order Saya --}}
<div class="card">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Order Saya</h6>
        @if($myOrders->isEmpty())
            <p class="text-muted mb-0">Belum ada order yang diambil.</p>
        @else
            <table class="table table-borderless table-hover mb-0">
                <thead class="border-bottom">
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Pickup</th>
                        <th>Tujuan</th>
                        <th>Jarak</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myOrders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>{{ Str::limit($order->pickup, 25) }}</td>
                        <td>{{ Str::limit($order->destination, 25) }}</td>
                        <td>{{ number_format($order->distance, 2) }} km</td>
                        <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status === 'ongoing')
                                <span class="badge bg-info text-dark">Ongoing</span>
                            @elseif($order->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('chat.show', $order->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-chat-dots"></i>
                            </a>
                            @if($order->status == 'ongoing')
                                <form method="POST" action="{{ route('orders.complete', $order->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
