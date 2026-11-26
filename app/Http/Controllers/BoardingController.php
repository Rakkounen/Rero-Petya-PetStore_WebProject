<?php

namespace App\Http\Controllers;

use App\Models\BoardingRoom;
use Illuminate\Contracts\View\View;

class BoardingController extends Controller
{
    public function index(): View
    {
        $rooms = BoardingRoom::query()
            ->where('status', 'tersedia')
            ->orderBy('price_per_day')
            ->get(['id', 'code', 'name', 'room_type', 'pet_species', 'size', 'price_per_day', 'facilities']);

        return view('boarding.index', ['rooms' => $rooms]);
    }
}
