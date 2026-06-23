@extends('layouts.main')

@section('title', 'Edit Order #' . $order->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h4 class="fw-bold mb-0">Edit Order <span class="text-muted">#{{ $order->id }}</span></h4>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-body bg-light rounded">
                <p class="mb-1"><strong>Pickup:</strong> {{ $order->pickup }}</p>
                <p class="mb-1"><strong>Destination:</strong> {{ $order->destination }}</p>
                <p class="mb-1"><strong>Kendaraan:</strong> {{ $order->vehicle_type }}</p>
                <p class="mb-0"><strong>Jarak:</strong> {{ number_format($order->distance, 2) }} km</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga (Rp)</label>
                        <input type="number" name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $order->price) }}" min="0" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="pending" {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="ongoing" {{ old('status', $order->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status', $order->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
