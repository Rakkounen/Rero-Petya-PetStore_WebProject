<?php

namespace App\Http\Controllers;

use App\Models\BoardingRoom;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');

        $services = Service::query()
            ->where('is_active', true)
            ->where('type', 'grooming')
            ->when(
                $type === 'penitipan',
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->orderBy('name')
            ->when(
                $type === 'penitipan',
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->paginate(12, ['*'], 'services_page')
            ->withQueryString();

        $rooms = BoardingRoom::query()
            ->where('status', 'tersedia')
            ->when(
                $type === 'grooming',
                fn ($query) => $query->whereRaw('1 = 0'),
            )
            ->orderBy('price_per_day')
            ->paginate(12, ['*'], 'rooms_page')
            ->withQueryString();

        return view('services.index', [
            'services' => $services,
            'rooms' => $rooms,
            'type' => $type,
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_active && $service->type === 'grooming', 404);

        return view('services.show', ['service' => $service]);
    }

    public function showBoardingRoom(BoardingRoom $boardingRoom): View
    {
        abort_unless($boardingRoom->status === 'tersedia', 404);

        return view('services.boarding-show', ['room' => $boardingRoom]);
    }
}
