<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
</head>
<body>
    <h2>Driver Dashboard</h2>
    <p>Selamat datang, {{ $user->name }}!</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <hr>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <h3>Status Akun</h3>
    <p>Status: <strong>{{ $driver->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}</strong></p>

    @if($driver->status == 'active')
        <p>Online: <strong>{{ $driver->available ? 'Online' : 'Offline' }}</strong></p>
        <form method="POST" action="/driver/toggle-available">
            @csrf
            @method('PATCH')
            <button type="submit">
                {{ $driver->available ? 'Set Offline' : 'Set Online' }}
            </button>
        </form>
    @else
        <p><em>Akun kamu belum diapprove admin. Harap tunggu.</em></p>
    @endif

    <hr>

    <h3>Profil Saya</h3>
    <table border="1" cellpadding="8">
        <tr>
            <td>Nama</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>{{ $driver->phone_number }}</td>
        </tr>
        <tr>
            <td>No. SIM</td>
            <td>{{ $driver->license_number }}</td>
        </tr>
    </table>

    <hr>

    <h3>Kendaraan Saya</h3>
    @if($driver->vehicle)
        <table border="1" cellpadding="8">
            <tr>
                <td>Merk</td>
                <td>{{ $driver->vehicle->brand }}</td>
            </tr>
            <tr>
                <td>Model</td>
                <td>{{ $driver->vehicle->model }}</td>
            </tr>
            <tr>
                <td>Warna</td>
                <td>{{ $driver->vehicle->color }}</td>
            </tr>
            <tr>
                <td>Plat Nomor</td>
                <td>{{ $driver->vehicle->plate_number }}</td>
            </tr>
            <tr>
                <td>Tahun</td>
                <td>{{ $driver->vehicle->year }}</td>
            </tr>
            <tr>
                <td>Tipe</td>
                <td>{{ $driver->vehicle->type }}</td>
            </tr>
            <tr>
                <td>Status Verifikasi</td>
                <td>{{ $driver->vehicle->verification_status }}</td>
            </tr>
        </table>
    @else
        <p>Belum ada kendaraan terdaftar.</p>
    @endif
</body>
</html>