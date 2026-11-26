@extends('layouts.app')

@section('title', 'Tentang Kami - Rero Petya')

@section('content')
    <section class="grid gap-8">
        <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm sm:p-10">
            <p class="eyebrow">Mengenal Rero Petya</p>
            <h1 class="page-title">Sahabat kebutuhan hewan kesayangan</h1>
            <p class="page-copy">
                Rero Petya adalah toko hewan peliharaan yang menyediakan produk dan layanan untuk membantu pemilik
                merawat hewan kesayangannya. Kami menyediakan makanan dan aksesoris hewan, layanan grooming, serta
                penitipan anjing dan kucing dengan tarif yang dihitung per hari.
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <article class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-ink">Produk hewan</h2>
                <p class="mt-3 leading-7 text-muted">
                    Pilihan makanan dan aksesoris untuk memenuhi kebutuhan hewan peliharaan.
                </p>
            </article>
            <article class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-ink">Grooming</h2>
                <p class="mt-3 leading-7 text-muted">
                    Layanan perawatan untuk membantu menjaga kebersihan dan kenyamanan hewan.
                </p>
            </article>
            <article class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-ink">Penitipan</h2>
                <p class="mt-3 leading-7 text-muted">
                    Layanan penitipan anjing dan kucing dengan tarif yang dihitung berdasarkan jumlah hari.
                </p>
            </article>
        </div>

        <div class="flex flex-wrap gap-3">
            <a class="button" href="{{ route('services.index') }}">Jelajahi layanan</a>
            <a class="button-secondary" href="{{ route('products.index') }}">Lihat produk</a>
        </div>
    </section>
@endsection
