<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Order #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5" style="max-width: 600px;">

    <h1 class="mb-4">Edit Order <span class="text-muted">#{{ $order->id }}</span></h1>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm mb-3">
        ← Kembali
    </a>

    {{-- Error validasi --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Info read-only --}}
    <div class="card mb-4">
        <div class="card-body bg-light">
            <p class="mb-1"><strong>Pickup:</strong> {{ $order->pickup }}</p>
            <p class="mb-1"><strong>Destination:</strong> {{ $order->destination }}</p>
            <p class="mb-1"><strong>Kendaraan:</strong> {{ $order->vehicle_type }}</p>
            <p class="mb-0"><strong>Jarak:</strong> {{ number_format($order->distance, 2) }} km</p>
        </div>
    </div>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Harga --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Harga (Rp)</label>
            <input
                type="number"
                name="price"
                class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price', $order->price) }}"
                min="0"
                required
            >
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status"
                    class="form-select @error('status') is-invalid @enderror">
                <option value="pending"
                    {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option value="completed"
                    {{ old('status', $order->status) === 'completed' ? 'selected' : '' }}>
                    Completed
                </option>
                <option value="cancelled"
                    {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Update Order
        </button>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>