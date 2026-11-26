<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with('category:id,name,slug,type')
            ->orderBy('name')
            ->paginate(12, [
                'id',
                'product_category_id',
                'name',
                'slug',
                'brand',
                'pet_species',
                'description',
                'weight',
                'price',
                'stock',
                'image',
            ])
            ->withQueryString();

        return view('products.index', ['products' => $products]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('category:id,name,slug,type');

        return view('products.show', ['product' => $product]);
    }
}
