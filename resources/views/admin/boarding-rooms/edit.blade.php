@extends('layouts.app')

@section('title', 'Edit penitipan - Rero Petya')

@section('content')
    <section class="form-card">
        <p class="eyebrow">Penitipan per hari</p>
        <h1 class="page-title mb-7">Edit layanan penitipan</h1>
        @include('admin.boarding-rooms._form', [
            'room' => $room,
            'action' => route('admin.boarding-rooms.update', $room),
            'method' => 'PUT',
            'submitLabel' => 'Simpan perubahan',
        ])
    </section>
@endsection
