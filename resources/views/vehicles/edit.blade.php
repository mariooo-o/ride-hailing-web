@extends('layouts.main')

@section('title', 'Edit Kendaraan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h4 class="fw-bold mb-0">Edit Kendaraan</h4>
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

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Driver</label>
                        <select name="driver_id" class="form-select bg-light" disabled>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $vehicle->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand', $vehicle->brand) }}">
                            @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                                value="{{ old('model', $vehicle->model) }}">
                            @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna</label>
                            <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
                                value="{{ old('color', $vehicle->color) }}">
                            @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plat Nomor</label>
                            <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror"
                                value="{{ old('plate_number', $vehicle->plate_number) }}">
                            @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                value="{{ old('year', $vehicle->year) }}" min="1990" max="{{ date('Y') }}">
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="motorcycle" {{ $vehicle->type == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                <option value="car" {{ $vehicle->type == 'car' ? 'selected' : '' }}>Car</option>
                                <option value="car_xl" {{ $vehicle->type == 'car_xl' ? 'selected' : '' }}>Car XL</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
