@extends('layouts.main')

@section('title', 'Detail Kendaraan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h4 class="fw-bold mb-0">Detail Kendaraan</h4>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="35%">Driver</td>
                        <td><strong>{{ $vehicle->driver->user->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Merk</td>
                        <td>{{ $vehicle->brand }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Model</td>
                        <td>{{ $vehicle->model }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Warna</td>
                        <td>{{ $vehicle->color }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Plat Nomor</td>
                        <td>{{ $vehicle->plate_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tahun</td>
                        <td>{{ $vehicle->year }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tipe</td>
                        <td>{{ $vehicle->type }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Verifikasi</td>
                        <td>
                            @if($vehicle->verification_status == 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($vehicle->verification_status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning btn-sm">Edit</a>
        </div>
    </div>
</div>
@endsection
