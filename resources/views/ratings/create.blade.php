@extends('layouts.main')

@section('title', 'Beri Rating Driver')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h4 class="fw-bold mb-0">Beri Rating Driver</h4>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card mb-3">
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="35%">Driver</td>
                        <td><strong>{{ $order->driver->user->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Pickup</td>
                        <td>{{ Str::limit($order->pickup, 50) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tujuan</td>
                        <td>{{ Str::limit($order->destination, 50) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Harga</td>
                        <td>Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('ratings.store') }}">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="rated_id" value="{{ $order->driver->user->id }}">
                    <input type="hidden" name="type" value="driver">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bintang</label>
                        <select name="stars" class="form-select">
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Baik)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Baik)</option>
                            <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                            <option value="2">⭐⭐ (2 - Kurang)</option>
                            <option value="1">⭐ (1 - Sangat Kurang)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Komentar <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan pengalamanmu...">{{ old('comment') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Kirim Rating</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
