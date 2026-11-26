@if ($errors->any())
    <div class="alert alert-error" role="alert">
        <p class="mb-2 font-bold">Mohon periksa kembali isian berikut:</p>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form class="grid gap-5" method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="name">Nama layanan</label>
            <input id="name" name="name" value="{{ old('name', $room?->name) }}" maxlength="255" required>
        </div>
        <div class="field">
            <label for="code">Kode layanan</label>
            <input id="code" name="code" value="{{ old('code', $room?->code) }}" maxlength="20" required>
        </div>
        <div class="field">
            <label for="pet_species">Jenis hewan</label>
            <select id="pet_species" name="pet_species" required>
                @foreach (['anjing_kucing' => 'Anjing & Kucing', 'kelinci' => 'Kelinci', 'lainnya' => 'Lainnya'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('pet_species', $room?->pet_species ?? 'anjing_kucing') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="price_per_day">Tarif per hari (Rp)</label>
            <input id="price_per_day" name="price_per_day" type="number" min="0" step="0.01" value="{{ old('price_per_day', $room?->price_per_day) }}" required>
        </div>
        <div class="field">
            <label for="status">Status layanan</label>
            <select id="status" name="status" required>
                @foreach (['tersedia' => 'Tersedia', 'terisi' => 'Terisi', 'perawatan' => 'Perawatan'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $room?->status ?? 'tersedia') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="field">
        <label for="facilities">Fasilitas / deskripsi</label>
        <textarea id="facilities" name="facilities" maxlength="2000">{{ old('facilities', $room?->facilities) }}</textarea>
    </div>
    <div class="flex flex-wrap gap-3">
        <button class="button" type="submit">{{ $submitLabel }}</button>
        <a class="button-secondary" href="{{ route('admin.boarding-rooms.index') }}">Batal</a>
    </div>
</form>
