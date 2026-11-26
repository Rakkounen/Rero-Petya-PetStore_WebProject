@extends('layouts.app')

@section('title', 'Grooming - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Bersih, sehat, dan nyaman</p>
                <h1 class="page-title">Layanan grooming</h1>
                <p class="page-copy">Perawatan lembut untuk menjaga kebersihan dan kesehatan hewan.</p>
            </div>
        </div>

        <div class="catalog-grid">
            @forelse ($services as $service)
                <article class="catalog-card">
                    <div class="card-image-placeholder" aria-hidden="true">🐾</div>
                    <div class="card-body">
                        <h2 class="card-title">{{ $service->name }}</h2>
                        <p class="card-copy">{{ $service->description ?: 'Layanan pilihan dari Rero Petya.' }}</p>
                        <p class="text-sm text-muted">
                            {{ $service->pet_species === 'semua' ? 'Semua hewan' : ucfirst($service->pet_species) }}
                            @if ($service->duration_minutes)
                                · {{ $service->duration_minutes }} menit
                            @endif
                        </p>
                        <p class="price mt-auto">Rp{{ number_format((float) $service->price, 0, ',', '.') }}</p>
                        <a class="button" href="{{ route('services.show', $service) }}">Lihat detail</a>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl bg-white p-6 text-muted">Belum ada layanan grooming yang tersedia.</p>
            @endforelse
        </div>
    </section>
@endsection
