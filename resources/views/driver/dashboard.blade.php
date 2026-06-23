@extends('layouts.main')

@section('title', 'Driver Dashboard')

@section('content')

    <h2 class="mb-4">Selamat datang, {{ $user->name }}!</h2>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">Status Akun</h5>
        <p class="mb-2">
            Status:
            @if($driver->status == 'active')
                <span class="badge bg-success">Aktif</span>
            @else
                <span class="badge bg-secondary">Tidak Aktif</span>
            @endif
        </p>

        @if($driver->status == 'active')
            <p class="mb-3">
                Online:
                <span class="badge {{ $driver->available ? 'bg-success' : 'bg-secondary' }}">
                    {{ $driver->available ? 'Online' : 'Offline' }}
                </span>
            </p>
            <form method="POST" action="{{ route('driver.toggle-available') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-primary btn-sm">
                    {{ $driver->available ? 'Set Offline' : 'Set Online' }}
                </button>
            </form>
        @else
            <p class="text-muted mb-0"><em>Akun kamu belum diapprove admin. Harap tunggu.</em></p>
        @endif
    </div>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">Profil Saya</h5>
        <table class="table table-borderless mb-0">
            <tr>
                <td class="text-muted" style="width: 150px;">Nama</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td class="text-muted">Email</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td class="text-muted">No. HP</td>
                <td>{{ $driver->phone_number }}</td>
            </tr>
            <tr>
                <td class="text-muted">No. SIM</td>
                <td>{{ $driver->license_number }}</td>
            </tr>
        </table>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="mb-3">Kendaraan Saya</h5>
        @if($driver->vehicle)
            <table class="table table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width: 150px;">Merk</td>
                    <td>{{ $driver->vehicle->brand }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Model</td>
                    <td>{{ $driver->vehicle->model }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Warna</td>
                    <td>{{ $driver->vehicle->color }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Plat Nomor</td>
                    <td>{{ $driver->vehicle->plate_number }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tahun</td>
                    <td>{{ $driver->vehicle->year }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tipe</td>
                    <td>{{ $driver->vehicle->type }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Status Verifikasi</td>
                    <td>{{ $driver->vehicle->verification_status }}</td>
                </tr>
            </table>
        @else
            <p class="text-muted mb-0">Belum ada kendaraan terdaftar.</p>
        @endif
    </div>

    <div class="card p-4">
        <h5 class="mb-3">Menu</h5>
        <a href="{{ route('driver.orders') }}" class="btn btn-primary">
            Lihat Order Tersedia
        </a>
    </div>

@endsection