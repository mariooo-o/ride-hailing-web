@extends('layouts.main')

@section('title', 'Data Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Data Kendaraan</h4>
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Kendaraan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-borderless table-hover mb-0">
            <thead class="border-bottom">
                <tr>
                    <th>No</th>
                    <th>Driver</th>
                    <th>Merk</th>
                    <th>Model</th>
                    <th>Warna</th>
                    <th>Plat</th>
                    <th>Tahun</th>
                    <th>Tipe</th>
                    <th>Verifikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $index => $vehicle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vehicle->driver->user->name }}</td>
                    <td>{{ $vehicle->brand }}</td>
                    <td>{{ $vehicle->model }}</td>
                    <td>{{ $vehicle->color }}</td>
                    <td>{{ $vehicle->plate_number }}</td>
                    <td>{{ $vehicle->year }}</td>
                    <td>{{ $vehicle->type }}</td>
                    <td>
                        @if($vehicle->verification_status == 'verified')
                            <span class="badge bg-success">Verified</span>
                        @elseif($vehicle->verification_status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1 flex-wrap">
                        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="{{ route('vehicles.destroy', $vehicle->id) }}" class="d-inline" onsubmit="return confirm('Hapus kendaraan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                        @if($vehicle->verification_status == 'pending')
                            <form method="POST" action="{{ route('vehicles.verify', $vehicle->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Verify</button>
                            </form>
                            <form method="POST" action="{{ route('vehicles.reject', $vehicle->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Reject</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
