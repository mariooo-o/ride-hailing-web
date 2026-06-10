<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Driver</title>
</head>
<body>
    <h2>Edit Driver</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/drivers/{{ $driver->id }}">
        @csrf
        @method('PUT')
        <div>
            <label>Nama</label><br>
            <input type="text" name="name" value="{{ old('name', $driver->user->name) }}">
        </div>
        <br>
        <div>
            <label>No. HP</label><br>
            <input type="text" name="phone_number" value="{{ old('phone_number', $driver->phone_number) }}">
        </div>
        <br>
        <div>
            <label>No. SIM</label><br>
            <input type="text" name="license_number" value="{{ old('license_number', $driver->license_number) }}">
        </div>
        <br>
        <div>
            <label>Status</label><br>
            <select name="status">
                <option value="active" {{ $driver->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $driver->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <br>
        <button type="submit">Update</button>
        <a href="/drivers">Batal</a>
    </form>
</body>
</html>