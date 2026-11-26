<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('category:id,name')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.products.index', ['products' => $products]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => ProductCategory::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::query()->create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', "Produk {$product->name} berhasil ditambahkan.");
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => ProductCategory::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request, $product);
        unset($data['remove_image']);

        if ($request->hasFile('image')) {
            $oldImage = $product->image;
            $data['image'] = $request->file('image')->store('products', 'public');
            $this->deleteImage($oldImage);
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($product->image);
            $data['image'] = null;
        } else {
            unset($data['image']);
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('status', "Produk {$product->name} berhasil diperbarui.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orderItems()->exists()) {
            $product->update(['is_active' => false]);

            return redirect()
                ->route('admin.products.index')
                ->with('status', 'Produk pernah terjual, jadi dinonaktifkan agar riwayat transaksi tetap utuh.');
        }

        $product->delete();
        $this->deleteImage($product->image);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk berhasil dihapus.');
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    private function validatedData(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'product_category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product)],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product)],
            'brand' => ['nullable', 'string', 'max:255'],
            'pet_species' => ['required', 'in:semua,anjing,kucing,kelinci,burung,hamster,lainnya'],
            'description' => ['nullable', 'string', 'max:5000'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'cost_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'expired_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function deleteImage(?string $path): void
    {
        if ($path !== null && ! Storage::disk('public')->delete($path)) {
            report(new RuntimeException("Unable to delete product image [{$path}]."));
        }
    }
}
