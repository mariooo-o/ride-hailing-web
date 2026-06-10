<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Driver</title>
</head>
<body>
    <h2>Detail Driver</h2>

    <table border="1" cellpadding="8">
        <tr>
            <td>Nama</td>
            <td>{{ $driver->user->name }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $driver->user->email }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>{{ $driver->phone_number }}</td>
        </tr>
        <tr>
            <td>No. SIM</td>
            <td>{{ $driver->license_number }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{ $driver->status }}</td>
        </tr>
        <tr>
            <td>Kendaraan</td>
            <td>
                @if($driver->vehicle)
                    {{ $driver->vehicle->brand }} {{ $driver->vehicle->model }} - 
                    {{ $driver->vehicle->plate_number }}
                @else
                    Belum ada kendaraan
                @endif
            </td>
        </tr>
    </table>

    <br>
    <a href="/drivers/{{ $driver->id }}/edit">Edit</a> |
    <a href="/drivers">Kembali</a>
</body>
</html>