<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Rating</title>
</head>
<body>
    <h2>Beri Rating Driver</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <table border="1" cellpadding="8">
        <tr>
            <td>Driver</td>
            <td>{{ $order->driver->user->name }}</td>
        </tr>
        <tr>
            <td>Pickup</td>
            <td>{{ $order->pickup }}</td>
        </tr>
        <tr>
            <td>Tujuan</td>
            <td>{{ $order->destination }}</td>
        </tr>
        <tr>
            <td>Harga</td>
            <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
        </tr>
    </table>

    <br>

    <form method="POST" action="/ratings">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="rated_id" value="{{ $order->driver->user->id }}">
        <input type="hidden" name="type" value="driver">

        <div>
            <label>Bintang</label><br>
            <select name="stars">
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select>
        </div>
        <br>
        <div>
            <label>Komentar (opsional)</label><br>
            <textarea name="comment" rows="3" cols="40">{{ old('comment') }}</textarea>
        </div>
        <br>
        <button type="submit">Kirim Rating</button>
        <a href="{{ route('orders.index') }}">Batal</a>
    </form>
</body>
</html>