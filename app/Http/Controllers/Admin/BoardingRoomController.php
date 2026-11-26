<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingRoom;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BoardingRoomController extends Controller
{
    public function index(): View
    {
        $rooms = BoardingRoom::query()
            ->orderBy('room_type')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.boarding-rooms.index', ['rooms' => $rooms]);
    }

    public function create(): View
    {
        return view('admin.boarding-rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $room = BoardingRoom::query()->create($this->validatedData($request));

        return redirect()
            ->route('admin.boarding-rooms.index')
            ->with('status', "Layanan penitipan {$room->name} berhasil ditambahkan.");
    }

    public function edit(BoardingRoom $boardingRoom): View
    {
        return view('admin.boarding-rooms.edit', ['room' => $boardingRoom]);
    }

    public function update(Request $request, BoardingRoom $boardingRoom): RedirectResponse
    {
        $boardingRoom->update($this->validatedData($request, $boardingRoom));

        return redirect()
            ->route('admin.boarding-rooms.index')
            ->with('status', "Layanan penitipan {$boardingRoom->name} berhasil diperbarui.");
    }

    public function destroy(BoardingRoom $boardingRoom): RedirectResponse
    {
        if ($boardingRoom->boardings()->exists()) {
            $boardingRoom->update(['status' => 'perawatan']);

            return redirect()
                ->route('admin.boarding-rooms.index')
                ->with('status', 'Layanan dengan riwayat penitipan dipindahkan ke status perawatan agar riwayat tetap utuh.');
        }

        $boardingRoom->delete();

        return redirect()
            ->route('admin.boarding-rooms.index')
            ->with('status', 'Layanan penitipan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?BoardingRoom $room = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('boarding_rooms', 'code')->ignore($room)],
            'name' => ['required', 'string', 'max:255'],
            'pet_species' => ['required', 'in:anjing_kucing,kelinci,lainnya'],
            'price_per_day' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'facilities' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:tersedia,terisi,perawatan'],
        ]);

        return [
            ...$data,
            'room_type' => $room?->room_type ?? 'standar',
            'size' => $room?->size ?? 'sedang',
        ];
    }
}
