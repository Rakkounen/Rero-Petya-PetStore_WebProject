@extends('layouts.app')

@section('title', 'Layanan hewan - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Perawatan dengan sepenuh hati</p>
                <h1 class="page-title">Layanan Rero Petya</h1>
                <p class="page-copy">Pilih layanan grooming atau penitipan. Tarif penitipan dihitung per hari.</p>
            </div>
        </div>

        <nav class="filter-row" aria-label="Filter layanan">
            <a class="button-secondary" href="{{ route('services.index') }}">Semua layanan</a>
            <a class="button-secondary" href="{{ route('services.index', ['type' => 'grooming']) }}">Grooming</a>
            <a class="button-secondary" href="{{ route('services.index', ['type' => 'penitipan']) }}">Penitipan</a>
        </nav>

        @if ($type !== 'penitipan')
            <h2 class="mb-5 text-2xl font-bold">Grooming</h2>
            <div class="catalog-grid">
                @forelse ($services as $service)
                    <article class="catalog-card">
                        <div class="card-image-placeholder" aria-hidden="true">🐾</div>
                        <div class="card-body">
                            <span class="badge">Grooming</span>
                            <h3 class="card-title">{{ $service->name }}</h3>
                            <p class="card-copy">{{ $service->description ?: 'Layanan pilihan dari Rero Petya.' }}</p>
                            <p class="text-sm text-muted">
                                {{ $service->pet_species === 'semua' ? 'Semua hewan' : ucfirst($service->pet_species) }}
                                @if ($service->duration_minutes)
                                    · {{ $service->duration_minutes }} menit
                                @endif
                            </p>
                            <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-2">
                                <span class="price">Rp{{ number_format((float) $service->price, 0, ',', '.') }}</span>
                                <a class="button" href="{{ route('services.show', $service) }}">Lihat detail</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="rounded-2xl bg-white p-6 text-muted">Belum ada layanan grooming yang tersedia.</p>
                @endforelse
            </div>
            <div class="pagination">{{ $services->links() }}</div>
        @endif

        @if ($type !== 'grooming')
            <div class="mt-12">
                <p class="eyebrow">Penitipan dengan nyaman</p>
                <h2 class="mb-5 text-2xl font-bold">Penitipan hewan · tarif per hari</h2>
                <div class="catalog-grid">
                    @forelse ($rooms as $room)
                        <article class="catalog-card">
                            <div class="card-image-placeholder" aria-hidden="true">🏠</div>
                            <div class="card-body">
                                <span class="badge">{{ $room->pet_species === 'anjing_kucing' ? 'Anjing & Kucing' : ucfirst($room->pet_species) }}</span>
                                <h3 class="card-title">{{ $room->name }}</h3>
                                <p class="card-copy">{{ $room->facilities ?: 'Tempat penitipan nyaman untuk hewan kesayangan Anda.' }}</p>
                                <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-2">
                                    <span class="price">Rp{{ number_format((float) $room->price_per_day, 0, ',', '.') }} / hari</span>
                                    <a class="button" href="{{ route('services.boarding.show', $room) }}">Lihat detail</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="rounded-2xl bg-white p-6 text-muted">Belum ada layanan penitipan yang tersedia.</p>
                    @endforelse
                </div>
                <div class="pagination">{{ $rooms->links() }}</div>
            </div>
        @endif
    </section>
@endsection
