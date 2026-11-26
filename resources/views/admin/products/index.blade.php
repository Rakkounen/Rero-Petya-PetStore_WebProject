@extends('layouts.app')

@section('title', 'Kelola produk - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Pengelolaan katalog</p>
                <h1 class="page-title">Produk</h1>
            </div>
            <a class="button" href="{{ route('admin.products.create') }}">Tambah produk</a>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="font-semibold">{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td><span class="badge">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <a class="button-secondary" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus atau nonaktifkan produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $products->links() }}</div>
    </section>
@endsection
