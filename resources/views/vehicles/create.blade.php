<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kendaraan</title>
</head>
<body>
    <h2>Tambah Kendaraan</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/vehicles">
        @csrf
        <div>
            <label>Driver</label><br>
            <select name="driver_id">
                <option value="">-- Pilih Driver --</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <br>
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
            <label>Tipe</label><br>
            <select name="type">
                <option value="motorcycle" {{ old('type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                <option value="car" {{ old('type') == 'car' ? 'selected' : '' }}>Car</option>
                <option value="car_xl" {{ old('type') == 'car_xl' ? 'selected' : '' }}>Car XL</option>
            </select>
        </div>
        <br>
        <button type="submit">Simpan</button>
        <a href="/vehicles">Batal</a>
    </form>
</body>
</html>