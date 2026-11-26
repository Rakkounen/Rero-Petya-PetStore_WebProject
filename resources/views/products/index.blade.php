@extends('layouts.app')

@section('title', 'Makanan dan aksesoris - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Temani hari-hari mereka</p>
                <h1 class="page-title">Makanan & aksesoris</h1>
                <p class="page-copy">Temukan kebutuhan pilihan untuk hewan kesayangan Anda.</p>
            </div>
        </div>

        <div class="catalog-grid">
            @forelse ($products as $product)
                <article class="catalog-card">
                    @if ($product->image)
                        <img class="card-image" src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="card-image-placeholder" aria-hidden="true">🛍️</div>
                    @endif
                    <div class="card-body">
                        <span class="badge">{{ $product->category->name }}</span>
                        <h2 class="card-title">{{ $product->name }}</h2>
                        @if ($product->brand)
                            <p class="text-sm text-muted">{{ $product->brand }}</p>
                        @endif
                        <p class="card-copy">{{ $product->description ?: 'Produk pilihan dari Rero Petya.' }}</p>
                        <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-2">
                            <span class="price">Rp{{ number_format((float) $product->price, 0, ',', '.') }}</span>
                            <a class="button" href="{{ route('products.show', $product) }}">Lihat detail</a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl bg-white p-6 text-muted">Belum ada produk yang tersedia.</p>
            @endforelse
        </div>

        <div class="pagination">{{ $products->links() }}</div>
    </section>
@endsection
