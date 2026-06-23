@extends('layouts.main')

@section('title', 'Detail Driver')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h4 class="fw-bold mb-0">Detail Driver</h4>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="35%">Nama</td>
                        <td><strong>{{ $driver->user->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $driver->user->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. HP</td>
                        <td>{{ $driver->phone_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. SIM</td>
                        <td>{{ $driver->license_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($driver->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning text-dark">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kendaraan</td>
                        <td>
                            @if($driver->vehicle)
                                {{ $driver->vehicle->brand }} {{ $driver->vehicle->model }} - {{ $driver->vehicle->plate_number }}
                            @else
                                <span class="text-muted">Belum ada kendaraan</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-warning btn-sm">Edit</a>
        </div>
    </div>
</div>
@endsection
