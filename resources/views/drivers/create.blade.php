<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Driver</title>
</head>
<body>
    <h2>Tambah Driver</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/drivers">
        @csrf
        <div>
            <label>Nama</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>
        <br>
        <div>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>
        <br>
        <div>
            <label>No. HP</label><br>
            <input type="text" name="phone_number" value="{{ old('phone_number') }}">
        </div>
        <br>
        <div>
            <label>No. SIM</label><br>
            <input type="text" name="license_number" value="{{ old('license_number') }}">
        </div>
        <br>
        <div>
            <label>Password</label><br>
            <input type="password" name="password">
        </div>
        <br>
        <button type="submit">Simpan</button>
        <a href="/drivers">Batal</a>
    </form>
</body>
</html>