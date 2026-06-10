<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Driver</title>
</head>
<body>
    <h2>Data Driver</h2>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="/drivers/create">+ Tambah Driver</a>
    <br><br>

    <table border="1" cellpadding="8">
        <thead>
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
                <td>{{ $driver->status }}</td>
                <td>
                    <a href="/drivers/{{ $driver->id }}">Detail</a> |
                    <a href="/drivers/{{ $driver->id }}/edit">Edit</a> |
                    <form method="POST" action="/drivers/{{ $driver->id }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus driver ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>