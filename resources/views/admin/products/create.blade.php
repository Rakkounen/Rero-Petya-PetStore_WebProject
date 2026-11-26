@extends('layouts.app')

@section('title', 'Tambah produk - Rero Petya')

@section('content')
    <section class="form-card">
        <p class="eyebrow">Katalog produk</p>
        <h1 class="page-title mb-7">Tambah produk</h1>
        @include('admin.products._form', [
            'product' => null,
            'action' => route('admin.products.store'),
            'method' => 'POST',
            'submitLabel' => 'Simpan produk',
        ])
    </section>
@endsection
