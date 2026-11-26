@extends('layouts.app')

@section('title', $product->name.' - Produk Rero Petya')

@section('content')
    <a class="mb-6 inline-flex font-semibold text-brand-700" href="{{ route('products.index') }}">← Kembali ke produk</a>
    <article class="detail-panel">
        <div>
            @if ($product->image)
                <img class="aspect-square w-full rounded-2xl bg-brand-50 object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="card-image-placeholder aspect-square rounded-2xl text-7xl" aria-hidden="true">🛍️</div>
            @endif
        </div>
        <div class="flex flex-col items-start gap-5 py-2">
            <span class="badge">{{ $product->category->name }}</span>
            <h1 class="page-title">{{ $product->name }}</h1>
            @if ($product->brand)
                <p class="text-sm font-semibold text-muted">{{ $product->brand }}</p>
            @endif
            <p class="detail-copy">{{ $product->description ?: 'Produk pilihan dari Rero Petya.' }}</p>
            <p class="text-sm text-muted">
                {{ $product->pet_species === 'semua' ? 'Untuk semua hewan' : 'Untuk '.ucfirst($product->pet_species) }}
                @if ($product->weight)
                    · Berat {{ $product->weight }} kg
                @endif
            </p>
            <p class="price text-2xl">Rp{{ number_format((float) $product->price, 0, ',', '.') }}</p>
            <p class="text-sm {{ $product->stock > 0 ? 'text-brand-700' : 'text-rose-700' }}">
                {{ $product->stock > 0 ? 'Stok tersedia' : 'Stok sedang habis' }}
            </p>
            <a class="button" href="{{ route('products.index') }}">Jelajahi produk lainnya</a>
        </div>
    </article>
@endsection
