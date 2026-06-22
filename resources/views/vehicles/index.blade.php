<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan</title>
</head>
<body>
    <h2>Data Kendaraan</h2>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="/vehicles/create">+ Tambah Kendaraan</a>
    <br><br>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Driver</th>
                <th>Merk</th>
                <th>Model</th>
                <th>Warna</th>
                <th>Plat</th>
                <th>Tahun</th>
                <th>Tipe</th>
                <th>Status Verifikasi</th>
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
                <td>{{ $vehicle->verification_status }}</td>
                <td>
                    <a href="/vehicles/{{ $vehicle->id }}">Detail</a> |
                    <a href="/vehicles/{{ $vehicle->id }}/edit">Edit</a> |
                    <form method="POST" action="/vehicles/{{ $vehicle->id }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus kendaraan ini?')">Hapus</button>
                    </form>
                    |
                    @if($vehicle->verification_status == 'pending')
                        <form method="POST" action="/vehicles/{{ $vehicle->id }}/verify" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Verify</button>
                        </form>
                        |
                        <form method="POST" action="/vehicles/{{ $vehicle->id }}/reject" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Reject</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>