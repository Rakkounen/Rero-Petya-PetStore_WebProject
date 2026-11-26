@if ($errors->any())
    <div class="alert alert-error" role="alert">
        <p class="mb-2 font-bold">Mohon periksa kembali isian berikut:</p>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form class="grid gap-5" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="name">Nama produk</label>
            <input id="name" name="name" value="{{ old('name', $product?->name) }}" maxlength="255" required>
        </div>
        <div class="field">
            <label for="product_category_id">Kategori</label>
            <select id="product_category_id" name="product_category_id" required>
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('product_category_id', $product?->product_category_id) === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="sku">SKU</label>
            <input id="sku" name="sku" value="{{ old('sku', $product?->sku) }}" maxlength="50" required>
        </div>
        <div class="field">
            <label for="slug">Slug URL</label>
            <input id="slug" name="slug" value="{{ old('slug', $product?->slug) }}" maxlength="255" required>
        </div>
        <div class="field">
            <label for="brand">Merek (opsional)</label>
            <input id="brand" name="brand" value="{{ old('brand', $product?->brand) }}" maxlength="255">
        </div>
        <div class="field">
            <label for="pet_species">Untuk hewan</label>
            <select id="pet_species" name="pet_species" required>
                @foreach (['semua' => 'Semua hewan', 'anjing' => 'Anjing', 'kucing' => 'Kucing', 'kelinci' => 'Kelinci', 'burung' => 'Burung', 'hamster' => 'Hamster', 'lainnya' => 'Lainnya'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('pet_species', $product?->pet_species ?? 'semua') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="cost_price">Harga modal (Rp)</label>
            <input id="cost_price" name="cost_price" type="number" min="0" step="0.01" value="{{ old('cost_price', $product?->cost_price ?? 0) }}" required>
        </div>
        <div class="field">
            <label for="price">Harga jual (Rp)</label>
            <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product?->price) }}" required>
        </div>
        <div class="field">
            <label for="weight">Berat (kg, opsional)</label>
            <input id="weight" name="weight" type="number" min="0" step="0.01" value="{{ old('weight', $product?->weight) }}">
        </div>
        <div class="field">
            <label for="stock">Stok</label>
            <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required>
        </div>
        <div class="field">
            <label for="min_stock">Batas stok minimum</label>
            <input id="min_stock" name="min_stock" type="number" min="0" value="{{ old('min_stock', $product?->min_stock ?? 5) }}" required>
        </div>
        <div class="field">
            <label for="expired_date">Tanggal kedaluwarsa (opsional)</label>
            <input id="expired_date" name="expired_date" type="date" value="{{ old('expired_date', $product?->expired_date?->format('Y-m-d')) }}">
        </div>
    </div>
    <div class="field">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" maxlength="5000">{{ old('description', $product?->description) }}</textarea>
    </div>
    <div class="field">
        <label for="image">Foto (JPG, PNG, atau WebP; maks. 2 MB)</label>
        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        @if ($product?->image)
            <div class="flex flex-wrap items-center gap-3">
                <img class="h-20 w-20 rounded-xl object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}">
                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="remove_image" value="1">
                    Hapus foto saat disimpan
                </label>
            </div>
        @endif
    </div>
    <label class="flex items-center gap-3 text-sm font-semibold">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true))>
        Tampilkan sebagai produk aktif
    </label>
    <div class="flex flex-wrap gap-3">
        <button class="button" type="submit">{{ $submitLabel }}</button>
        <a class="button-secondary" href="{{ route('admin.products.index') }}">Batal</a>
    </div>
</form>
