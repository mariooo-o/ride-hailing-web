@extends('layouts.main')

@section('title', 'Edit Driver')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h4 class="fw-bold mb-0">Edit Driver</h4>
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
                <form method="POST" action="{{ route('drivers.update', $driver->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $driver->user->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                            value="{{ old('phone_number', $driver->phone_number) }}">
                        @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. SIM</label>
                        <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror"
                            value="{{ old('license_number', $driver->license_number) }}">
                        @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $driver->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $driver->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
