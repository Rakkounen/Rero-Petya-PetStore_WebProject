@extends('layouts.app')

@section('title', 'Edit produk - Rero Petya')

@section('content')
    <section class="form-card">
        <p class="eyebrow">Katalog produk</p>
        <h1 class="page-title mb-7">Edit produk</h1>
        @include('admin.products._form', [
            'product' => $product,
            'action' => route('admin.products.update', $product),
            'method' => 'PUT',
            'submitLabel' => 'Simpan perubahan',
        ])
    </section>
@endsection
