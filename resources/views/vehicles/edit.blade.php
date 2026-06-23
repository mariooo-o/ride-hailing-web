<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kendaraan</title>
</head>
<body>
    <h2>Edit Kendaraan</h2>

    @if($errors->any())
        <p>{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/vehicles/{{ $vehicle->id }}">
        @csrf
        @method('PUT')
        <div>
            <label>Driver</label><br>
            <select name="driver_id" disabled>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ $vehicle->driver_id == $driver->id ? 'selected' : '' }}>
                        {{ $driver->user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <br>
        <div>
            <label>Merk</label><br>
            <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}">
        </div>
        <br>
        <div>
            <label>Model</label><br>
            <input type="text" name="model" value="{{ old('model', $vehicle->model) }}">
        </div>
        <br>
        <div>
            <label>Warna</label><br>
            <input type="text" name="color" value="{{ old('color', $vehicle->color) }}">
        </div>
        <br>
        <div>
            <label>Plat Nomor</label><br>
            <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}">
        </div>
        <br>
        <div>
            <label>Tahun</label><br>
            <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" min="1990" max="{{ date('Y') }}">
        </div>
        <br>
        <div>
            <label>Tipe</label><br>
            <select name="type">
                <option value="motorcycle" {{ $vehicle->type == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                <option value="car" {{ $vehicle->type == 'car' ? 'selected' : '' }}>Car</option>
                <option value="car_xl" {{ $vehicle->type == 'car_xl' ? 'selected' : '' }}>Car XL</option>
            </select>
        </div>
        <br>
        <button type="submit">Update</button>
        <a href="/vehicles">Batal</a>
    </form>
</body>
</html>