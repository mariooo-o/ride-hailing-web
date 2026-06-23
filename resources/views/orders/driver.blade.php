<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Driver</title>
</head>
<body>
    <h2>Order Tersedia</h2>

    <a href="/driver/dashboard">← Kembali ke Dashboard</a>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <h3>Order Pending</h3>
    @if($pendingOrders->isEmpty())
        <p>Tidak ada order pending saat ini.</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
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
                    <td>{{ $order->pickup }}</td>
                    <td>{{ $order->destination }}</td>
                    <td>{{ number_format($order->distance, 2) }} km</td>
                    <td>{{ $order->vehicle_type }}</td>
                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="/driver/orders/{{ $order->id }}/take">
                            @csrf
                            <button type="submit">Ambil Order</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <h3>Order Saya</h3>
    @if($myOrders->isEmpty())
        <p>Belum ada order yang diambil.</p>
    @else
        <table border="1" cellpadding="8">
            <thead>
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
                    <td>{{ $order->pickup }}</td>
                    <td>{{ $order->destination }}</td>
                    <td>{{ number_format($order->distance, 2) }} km</td>
                    <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        @if($order->status == 'ongoing')
                            <form method="POST" action="/orders/{{ $order->id }}/complete">
                                @csrf
                                <button type="submit">Selesai</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>