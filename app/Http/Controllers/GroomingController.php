<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Contracts\View\View;

class GroomingController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->where('type', 'grooming')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'description', 'price', 'duration_minutes', 'pet_species']);

        return view('grooming.index', ['services' => $services]);
    }
}
