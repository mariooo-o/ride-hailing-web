@extends('layouts.main')

@section('title', 'Data Driver')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Data Driver</h4>
    <a href="{{ route('drivers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Driver
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-borderless table-hover mb-0">
            <thead class="border-bottom">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>No. SIM</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drivers as $index => $driver)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $driver->user->name }}</td>
                    <td>{{ $driver->user->email }}</td>
                    <td>{{ $driver->phone_number }}</td>
                    <td>{{ $driver->license_number }}</td>
                    <td>
                        @if($driver->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">Inactive</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1 flex-wrap">
                        <a href="{{ route('drivers.show', $driver->id) }}" class="btn btn-secondary btn-sm">Detail</a>
                        <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="{{ route('drivers.destroy', $driver->id) }}" class="d-inline" onsubmit="return confirm('Hapus driver ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                        @if($driver->status == 'inactive')
                            <form method="POST" action="{{ route('drivers.approve', $driver->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('drivers.suspend', $driver->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Suspend</button>
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
