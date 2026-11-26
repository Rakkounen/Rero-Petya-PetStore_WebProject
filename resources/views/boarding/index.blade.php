@extends('layouts.app')

@section('title', 'Penitipan - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Aman dan nyaman saat Anda bepergian</p>
                <h1 class="page-title">Penitipan hewan</h1>
                <p class="page-copy">Lihat pilihan layanan penitipan dengan tarif per hari.</p>
            </div>
        </div>

        <h2 class="mb-5 text-2xl font-bold">Pilihan layanan penitipan</h2>
        <div class="catalog-grid">
            @forelse ($rooms as $room)
                <article class="catalog-card">
                    <div class="card-image-placeholder" aria-hidden="true">🏠</div>
                    <div class="card-body">
                        <span class="badge">{{ $room->pet_species === 'anjing_kucing' ? 'Anjing & Kucing' : ucfirst($room->pet_species) }}</span>
                        <h3 class="card-title">{{ $room->name }}</h3>
                        @if ($room->facilities)
                            <p class="text-sm text-muted">{{ $room->facilities }}</p>
                        @endif
                        <p class="price mt-auto">Rp{{ number_format((float) $room->price_per_day, 0, ',', '.') }} / hari</p>
                        <a class="button" href="{{ route('services.boarding.show', $room) }}">Lihat detail penitipan</a>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl bg-white p-6 text-muted">Belum ada layanan penitipan yang tersedia.</p>
            @endforelse
        </div>

    </section>
@endsection
