<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .suggestions-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 999;
            max-height: 220px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: white;
        }

        .suggestions-list li {
            padding: 10px 14px;
            cursor: pointer;
            font-size: 0.875rem;
            border-bottom: 1px solid #f0f0f0;
            list-style: none;
        }

        .suggestions-list li:last-child {
            border-bottom: none;
        }

        .suggestions-list li:hover {
            background-color: #f0f7ff;
            color: #0d6efd;
        }

        .suggestions-list .loading-item {
            color: #6c757d;
            font-style: italic;
            pointer-events: none;
        }

        .input-wrapper {
            position: relative;
        }
    </style>
</head>
<body>

<div class="container mt-5" style="max-width: 620px;">

    <h1 class="mb-4">Buat Order Baru</h1>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm mb-3">
        ← Kembali
    </a>

    {{-- Tampilkan error validasi --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        {{-- Pickup --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Lokasi Pickup</label>
            <div class="input-wrapper">
                <input
                    type="text"
                    name="pickup"
                    id="pickup"
                    class="form-control @error('pickup') is-invalid @enderror"
                    placeholder="Contoh: Universitas Tarumanagara Jakarta"
                    value="{{ old('pickup') }}"
                    autocomplete="off"
                    required
                >
                <ul class="suggestions-list" id="pickup-suggestions"></ul>
            </div>
            @error('pickup')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Destination --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Lokasi Tujuan</label>
            <div class="input-wrapper">
                <input
                    type="text"
                    name="destination"
                    id="destination"
                    class="form-control @error('destination') is-invalid @enderror"
                    placeholder="Contoh: Mall Taman Anggrek Jakarta"
                    value="{{ old('destination') }}"
                    autocomplete="off"
                    required
                >
                <ul class="suggestions-list" id="destination-suggestions"></ul>
            </div>
            @error('destination')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kendaraan --}}
        <div class="mb-4">
            <label class="form-label fw-bold">Jenis Kendaraan</label>
            <select name="vehicle_type"
                    class="form-select @error('vehicle_type') is-invalid @enderror">
                <option value="Motor" {{ old('vehicle_type') === 'Motor' ? 'selected' : '' }}>
                    Motor - Rp 2.500/km (min. Rp 10.000)
                </option>
                <option value="Mobil" {{ old('vehicle_type') === 'Mobil' ? 'selected' : '' }}>
                    Mobil - Rp 5.000/km (min. Rp 20.000)
                </option>
            </select>
            @error('vehicle_type')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
            Pesan Sekarang
        </button>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function setupAutocomplete(inputId, suggestionsId) {
    const input       = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    let delay;

    input.addEventListener('input', function () {
        clearTimeout(delay);
        const query = this.value.trim();

        if (query.length < 3) {
            suggestions.innerHTML = '';
            return;
        }

        suggestions.innerHTML = '<li class="loading-item">Mencari lokasi...</li>';

        delay = setTimeout(() => {
            const url = new URL('https://nominatim.openstreetmap.org/search');
            url.searchParams.set('q', query);
            url.searchParams.set('format', 'json');
            url.searchParams.set('limit', '5');
            url.searchParams.set('countrycodes', 'id');
            url.searchParams.set('viewbox', '106.6,-6.4,107.1,-5.9');
            url.searchParams.set('bounded', '1');

            fetch(url, {
                headers: { 'Accept-Language': 'id' }
            })
            .then(res => res.json())
            .then(data => {
                suggestions.innerHTML = '';

                if (data.length === 0) {
                    suggestions.innerHTML = '<li class="loading-item">Lokasi tidak ditemukan</li>';
                    return;
                }

                data.forEach(place => {
                    const li     = document.createElement('li');
                    const maxLen = 80;
                    const name   = place.display_name.length > maxLen
                        ? place.display_name.substring(0, maxLen) + '...'
                        : place.display_name;

                    li.textContent = name;
                    li.title       = place.display_name;

                    li.addEventListener('click', () => {
                        input.value           = place.display_name;
                        suggestions.innerHTML = '';
                    });

                    suggestions.appendChild(li);
                });
            })
            .catch(() => {
                suggestions.innerHTML = '<li class="loading-item">Gagal memuat saran lokasi</li>';
            });
        }, 500);
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.innerHTML = '';
        }
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            suggestions.innerHTML = '';
        }
    });
}

setupAutocomplete('pickup', 'pickup-suggestions');
setupAutocomplete('destination', 'destination-suggestions');
</script>

</body>
</html>