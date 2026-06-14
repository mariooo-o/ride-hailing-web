<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kendaraan</title>
</head>
<body>
    <h2>Detail Kendaraan</h2>

    <table border="1" cellpadding="8">
        <tr>
            <td>Driver</td>
            <td>{{ $vehicle->driver->user->name }}</td>
        </tr>
        <tr>
            <td>Merk</td>
            <td>{{ $vehicle->brand }}</td>
        </tr>
        <tr>
            <td>Model</td>
            <td>{{ $vehicle->model }}</td>
        </tr>
        <tr>
            <td>Warna</td>
            <td>{{ $vehicle->color }}</td>
        </tr>
        <tr>
            <td>Plat Nomor</td>
            <td>{{ $vehicle->plate_number }}</td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>{{ $vehicle->year }}</td>
        </tr>
        <tr>
            <td>Tipe</td>
            <td>{{ $vehicle->type }}</td>
        </tr>
        <tr>
            <td>Status Verifikasi</td>
            <td>{{ $vehicle->verification_status }}</td>
        </tr>
    </table>

    <br>
    <a href="/vehicles/{{ $vehicle->id }}/edit">Edit</a> |
    <a href="/vehicles">Kembali</a>
</body>
</html>