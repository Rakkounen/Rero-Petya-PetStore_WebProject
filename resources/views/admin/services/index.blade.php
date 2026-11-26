@extends('layouts.app')

@section('title', 'Kelola layanan - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Pengelolaan katalog</p>
                <h1 class="page-title">Layanan</h1>
            </div>
            <a class="button" href="{{ route('admin.services.create') }}">Tambah layanan</a>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td class="font-semibold">{{ $service->name }}</td>
                            <td>Grooming</td>
                            <td>Rp{{ number_format((float) $service->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge">{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a class="button-secondary" href="{{ route('admin.services.edit', $service) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Hapus atau nonaktifkan layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada layanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $services->links() }}</div>
    </section>
@endsection
