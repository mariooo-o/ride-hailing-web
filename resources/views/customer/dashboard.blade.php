@extends('layouts.main')

@section('title', 'Customer Dashboard')

@section('content')

    <h2 class="mb-4">Selamat datang, {{ Auth::user()->name }}!</h2>

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
                <td>{{ $user->phone_number ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="card p-4">
        <h5 class="mb-3">Menu</h5>
        <a href="{{ route('customer.daftar-driver') }}" class="btn btn-primary">
            Daftar Jadi Driver
        </a>
    </div>

@endsection