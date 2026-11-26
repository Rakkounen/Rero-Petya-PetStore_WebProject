@extends('layouts.app')

@section('title', 'Edit layanan - Rero Petya')

@section('content')
    <section class="form-card">
        <p class="eyebrow">Katalog layanan</p>
        <h1 class="page-title mb-7">Edit layanan</h1>
        @include('admin.services._form', [
            'service' => $service,
            'action' => route('admin.services.update', $service),
            'method' => 'PUT',
            'submitLabel' => 'Simpan perubahan',
        ])
    </section>
@endsection
