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
            <input id="name" name="name" value="{{ old('name', $service?->name) }}" maxlength="255" required>
        </div>
        <div class="field">
            <label for="type">Jenis layanan</label>
            <input id="type" type="hidden" name="type" value="grooming">
            <input class="min-h-11 rounded-xl border border-emerald-200 bg-brand-50 px-3 py-2 text-sm text-ink" value="Grooming" aria-label="Jenis layanan" disabled>
        </div>
        <div class="field">
            <label for="pet_species">Jenis hewan</label>
            <select id="pet_species" name="pet_species" required>
                @foreach (['semua' => 'Semua hewan', 'anjing' => 'Anjing', 'kucing' => 'Kucing', 'reptil' => 'Reptil', 'lainnya' => 'Lainnya'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('pet_species', $service?->pet_species ?? 'semua') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="price">Harga (Rp)</label>
            <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $service?->price) }}" required>
        </div>
        <div class="field">
            <label for="duration_minutes">Durasi (menit, opsional)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" min="1" max="65535" value="{{ old('duration_minutes', $service?->duration_minutes) }}">
        </div>
        <label class="flex items-center gap-3 self-end py-3 text-sm font-semibold">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service?->is_active ?? true))>
            Tampilkan sebagai layanan aktif
        </label>
    </div>
    <div class="field">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" maxlength="5000">{{ old('description', $service?->description) }}</textarea>
    </div>
    <div class="flex flex-wrap gap-3">
        <button class="button" type="submit">{{ $submitLabel }}</button>
        <a class="button-secondary" href="{{ route('admin.services.index') }}">Batal</a>
    </div>
</form>
