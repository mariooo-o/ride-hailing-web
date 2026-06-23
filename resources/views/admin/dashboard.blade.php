@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')

    <h2 class="mb-4">Selamat datang, {{ Auth::user()->name }}!</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Driver</div>
                <div class="fs-3 fw-bold">{{ $totalDrivers }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <div class="text-muted small">Driver Aktif</div>
                <div class="fs-3 fw-bold text-success">{{ $activeDrivers }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <div class="text-muted small">Driver Pending</div>
                <div class="fs-3 fw-bold text-warning">{{ $pendingDrivers }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Kendaraan</div>
                <div class="fs-3 fw-bold">{{ $totalVehicles }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <div class="text-muted small">Kendaraan Terverifikasi</div>
                <div class="fs-3 fw-bold text-success">{{ $verifiedVehicles }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <div class="text-muted small">Kendaraan Pending</div>
                <div class="fs-3 fw-bold text-warning">{{ $pendingVehicles }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <div class="text-muted small">Total Customer</div>
                <div class="fs-3 fw-bold">{{ $totalCustomers }}</div>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h5 class="mb-3">Menu</h5>
        <a href="{{ route('drivers.index') }}" class="btn btn-primary me-2">Kelola Driver</a>
        <a href="{{ route('vehicles.index') }}" class="btn btn-primary">Kelola Kendaraan</a>
    </div>

@endsection