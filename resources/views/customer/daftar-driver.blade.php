<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Driver</title>
</head>
<body>
    <h2>Daftar Jadi Driver</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/customer/daftar-driver">
        @csrf

        <h3>Data Diri</h3>
        <div>
            <label>Nama</label><br>
            <input type="text" value="{{ Auth::user()->name }}" disabled>
        </div>
        <br>
        <div>
            <label>Email</label><br>
            <input type="text" value="{{ Auth::user()->email }}" disabled>
        </div>
        <br>
        <div>
            <label>No. HP</label><br>
            <input type="text" name="phone_number" value="{{ old('phone_number', Auth::user()->phone_number) }}">
        </div>
        <br>
        <div>
            <label>No. SIM</label><br>
            <input type="text" name="license_number" value="{{ old('license_number') }}">
        </div>

        <hr>

        <h3>Data Kendaraan</h3>
        <div>
            <label>Merk</label><br>
            <input type="text" name="brand" value="{{ old('brand') }}">
        </div>
        <br>
        <div>
            <label>Model</label><br>
            <input type="text" name="model" value="{{ old('model') }}">
        </div>
        <br>
        <div>
            <label>Warna</label><br>
            <input type="text" name="color" value="{{ old('color') }}">
        </div>
        <br>
        <div>
            <label>Plat Nomor</label><br>
            <input type="text" name="plate_number" value="{{ old('plate_number') }}">
        </div>
        <br>
        <div>
            <label>Tahun</label><br>
            <input type="number" name="year" value="{{ old('year') }}" min="1990" max="{{ date('Y') }}">
        </div>
        <br>
        <div>
            <label>Tipe Kendaraan</label><br>
            <select name="type">
                <option value="motorcycle" {{ old('type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                <option value="car" {{ old('type') == 'car' ? 'selected' : '' }}>Car</option>
                <option value="car_xl" {{ old('type') == 'car_xl' ? 'selected' : '' }}>Car XL</option>
            </select>
        </div>
        <br>
        <button type="submit">Daftar</button>
        <a href="/customer/dashboard">Batal</a>
    </form>
</body>
</html>