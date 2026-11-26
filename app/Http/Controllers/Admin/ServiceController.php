<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->where('type', 'grooming')
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.services.index', ['services' => $services]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $service = Service::query()->create($this->validatedData($request));

        return redirect()
            ->route('admin.services.index')
            ->with('status', "Layanan {$service->name} berhasil ditambahkan.");
    }

    public function edit(Service $service): View
    {
        abort_unless($service->type === 'grooming', 404);

        return view('admin.services.edit', ['service' => $service]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        abort_unless($service->type === 'grooming', 404);

        $service->update($this->validatedData($request));

        return redirect()
            ->route('admin.services.index')
            ->with('status', "Layanan {$service->name} berhasil diperbarui.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        abort_unless($service->type === 'grooming', 404);

        if ($service->boardings()->exists() || $service->groomings()->exists()) {
            $service->update(['is_active' => false]);

            return redirect()
                ->route('admin.services.index')
                ->with('status', 'Layanan pernah digunakan, jadi dinonaktifkan agar riwayat transaksi tetap utuh.');
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Layanan berhasil dihapus.');
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'type' => ['required', 'in:grooming'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'pet_species' => ['required', 'in:semua,anjing,kucing,reptil,lainnya'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
