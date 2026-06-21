<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Selamat datang, {{ Auth::user()->name }}!</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <hr>

    <h3>Ringkasan</h3>
    <table border="1" cellpadding="8">
        <tr>
            <td>Total Driver</td>
            <td>{{ $totalDrivers }}</td>
        </tr>
        <tr>
            <td>Driver Aktif</td>
            <td>{{ $activeDrivers }}</td>
        </tr>
        <tr>
            <td>Driver Pending (belum diapprove)</td>
            <td>{{ $pendingDrivers }}</td>
        </tr>
        <tr>
            <td>Total Kendaraan</td>
            <td>{{ $totalVehicles }}</td>
        </tr>
        <tr>
            <td>Kendaraan Terverifikasi</td>
            <td>{{ $verifiedVehicles }}</td>
        </tr>
        <tr>
            <td>Kendaraan Pending</td>
            <td>{{ $pendingVehicles }}</td>
        </tr>
        <tr>
            <td>Total Customer</td>
            <td>{{ $totalCustomers }}</td>
        </tr>
    </table>

    <hr>

    <h3>Menu</h3>
    <a href="/drivers">Kelola Driver</a> |
    <a href="/vehicles">Kelola Kendaraan</a>
</body>
</html>