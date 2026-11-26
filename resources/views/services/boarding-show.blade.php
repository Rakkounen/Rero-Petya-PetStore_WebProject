@extends('layouts.app')

@section('title', $room->name.' - Penitipan Rero Petya')

@section('content')
    <a class="mb-6 inline-flex font-semibold text-brand-700" href="{{ route('services.index', ['type' => 'penitipan']) }}">← Kembali ke pilihan penitipan</a>
    <article class="detail-panel">
        <div class="card-image-placeholder aspect-square rounded-2xl text-7xl" aria-hidden="true">🏠</div>
        <div class="flex flex-col items-start gap-5 py-2">
            <span class="badge">{{ $room->pet_species === 'anjing_kucing' ? 'Anjing & Kucing' : ucfirst($room->pet_species) }}</span>
            <h1 class="page-title">{{ $room->name }}</h1>
            <p class="detail-copy">{{ $room->facilities ?: 'Tempat penitipan nyaman dan aman untuk hewan kesayangan Anda.' }}</p>
            <div>
                <p class="price text-2xl">Rp{{ number_format((float) $room->price_per_day, 0, ',', '.') }} / hari</p>
                <p class="mt-2 text-sm text-muted">Biaya penitipan dihitung per hari sesuai jumlah hari layanan.</p>
            </div>
            <p class="text-sm font-semibold text-brand-700">Status: tersedia</p>
            <a class="button" href="{{ route('services.index', ['type' => 'penitipan']) }}">Lihat layanan penitipan lainnya</a>
        </div>
    </article>
@endsection
