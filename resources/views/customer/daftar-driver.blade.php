@extends('layouts.main')

@section('title', 'Daftar Jadi Driver')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h4 class="fw-bold mb-0">Daftar Jadi Driver</h4>
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

        <form method="POST" action="{{ route('customer.submit-daftar-driver') }}">
            @csrf

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Data Diri</h6>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control bg-light" value="{{ Auth::user()->email }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                            value="{{ old('phone_number', Auth::user()->phone_number) }}">
                        @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">No. SIM</label>
                        <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                            value="{{ old('license_number') }}">
                        @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Data Kendaraan</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand') }}">
                            @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                                value="{{ old('model') }}">
                            @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna</label>
                            <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
                                value="{{ old('color') }}">
                            @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plat Nomor</label>
                            <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror"
                                value="{{ old('plate_number') }}">
                            @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                value="{{ old('year') }}" min="1990" max="{{ date('Y') }}">
                            @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="form-label">Tipe Kendaraan</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="motorcycle" {{ old('type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                <option value="car" {{ old('type') == 'car' ? 'selected' : '' }}>Car</option>
                                <option value="car_xl" {{ old('type') == 'car_xl' ? 'selected' : '' }}>Car XL</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Daftar</button>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
