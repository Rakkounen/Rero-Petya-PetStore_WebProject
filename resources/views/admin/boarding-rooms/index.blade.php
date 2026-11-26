@extends('layouts.app')

@section('title', 'Kelola penitipan - Rero Petya')

@section('content')
    <section>
        <div class="page-heading">
            <div>
                <p class="eyebrow">Tarif dan ketersediaan penitipan</p>
                <h1 class="page-title">Layanan penitipan</h1>
                <p class="page-copy">Tarif penitipan ditetapkan per hari.</p>
            </div>
            <a class="button" href="{{ route('admin.boarding-rooms.create') }}">Tambah penitipan</a>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Penitipan</th>
                        <th>Hewan</th>
                        <th>Tarif / hari</th>
                        <th>Status</th>
                        <th><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr>
                            <td>{{ $room->name }} <span class="block text-xs text-muted">{{ $room->code }}</span></td>
                            <td>{{ $room->pet_species === 'anjing_kucing' ? 'Anjing & Kucing' : ucfirst($room->pet_species) }}</td>
                            <td>Rp{{ number_format((float) $room->price_per_day, 0, ',', '.') }}</td>
                            <td><span class="badge">{{ ucfirst($room->status) }}</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <a class="button-secondary" href="{{ route('admin.boarding-rooms.edit', $room) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.boarding-rooms.destroy', $room) }}" onsubmit="return confirm('Hapus layanan penitipan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button-danger" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada layanan penitipan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $rooms->links() }}</div>
    </section>
@endsection
