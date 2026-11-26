<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::query()->count(),
            'serviceCount' => Service::query()->where('type', 'grooming')->count(),
            'activeProductCount' => Product::query()->where('is_active', true)->count(),
            'activeServiceCount' => Service::query()
                ->where('type', 'grooming')
                ->where('is_active', true)
                ->count(),
        ]);
    }
}
