@extends('layouts.app')

@section('title', $service->name.' - Layanan Rero Petya')

@section('content')
    <a class="mb-6 inline-flex font-semibold text-brand-700" href="{{ route('services.index') }}">← Kembali ke layanan</a>
    <article class="detail-panel">
        <div class="card-image-placeholder rounded-2xl text-7xl" aria-hidden="true">🐾</div>
        <div class="flex flex-col items-start gap-5 py-2">
            <span class="badge">Grooming</span>
            <h1 class="page-title">{{ $service->name }}</h1>
            <p class="detail-copy">{{ $service->description ?: 'Layanan pilihan dari Rero Petya untuk hewan kesayangan Anda.' }}</p>
            <p class="text-sm text-muted">
                {{ $service->pet_species === 'semua' ? 'Melayani semua hewan' : 'Untuk '.ucfirst($service->pet_species) }}
                @if ($service->duration_minutes)
                    · Durasi sekitar {{ $service->duration_minutes }} menit
                @endif
            </p>
            <p class="price text-2xl">Rp{{ number_format((float) $service->price, 0, ',', '.') }}</p>
            <a class="button" href="{{ route('services.index') }}">Jelajahi layanan lainnya</a>
        </div>
    </article>
@endsection
