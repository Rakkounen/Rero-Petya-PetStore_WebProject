@extends('layouts.app')

@section('title', 'Dashboard admin - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Panel pengelolaan</p>
                <h1 class="page-title">Dashboard admin</h1>
                <p class="page-copy">Kelola layanan dan katalog produk Rero Petya.</p>
            </div>
        </div>

        <div class="admin-grid">
            <article class="stat-card">
                <p class="text-sm font-semibold text-muted">Semua layanan</p>
                <p class="mt-3 text-3xl font-extrabold">{{ $serviceCount }}</p>
                <p class="mt-1 text-sm text-muted">{{ $activeServiceCount }} layanan aktif</p>
            </article>
            <article class="stat-card">
                <p class="text-sm font-semibold text-muted">Semua produk</p>
                <p class="mt-3 text-3xl font-extrabold">{{ $productCount }}</p>
                <p class="mt-1 text-sm text-muted">{{ $activeProductCount }} produk aktif</p>
            </article>
        </div>

        <div class="mt-7 flex flex-wrap gap-3">
            <a class="button" href="{{ route('admin.services.index') }}">Kelola layanan</a>
            <a class="button" href="{{ route('admin.boarding-rooms.index') }}">Kelola penitipan</a>
            <a class="button" href="{{ route('admin.products.index') }}">Kelola produk</a>
        </div>
    </section>
@endsection
