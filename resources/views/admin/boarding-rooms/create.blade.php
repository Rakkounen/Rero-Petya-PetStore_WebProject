@extends('layouts.app')

@section('title', 'Tambah penitipan - Rero Petya')

@section('content')
    <section class="form-card">
        <p class="eyebrow">Penitipan per hari</p>
        <h1 class="page-title mb-7">Tambah layanan penitipan</h1>
        @include('admin.boarding-rooms._form', [
            'room' => null,
            'action' => route('admin.boarding-rooms.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan layanan',
        ])
    </section>
@endsection
