@extends('layouts.app')

@section('title', 'Tambah layanan - Rero Petya')

@section('content')
    <section class="form-card">
        <p class="eyebrow">Katalog layanan</p>
        <h1 class="page-title mb-7">Tambah layanan</h1>
        @include('admin.services._form', [
            'service' => null,
            'action' => route('admin.services.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan layanan',
        ])
    </section>
@endsection
