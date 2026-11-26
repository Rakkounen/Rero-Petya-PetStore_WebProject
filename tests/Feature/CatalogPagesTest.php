<?php

namespace Tests\Feature;

use App\Models\BoardingRoom;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CatalogPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_penitipan_page_shows_available_rooms_without_add_on_services(): void
    {
        $availableRoom = BoardingRoom::query()->create([
            'code' => 'ROOM-01',
            'name' => 'Kamar Kucing',
            'price_per_day' => 100000,
            'pet_species' => 'kucing',
        ]);
        BoardingRoom::query()->create([
            'code' => 'ROOM-02',
            'name' => 'Kamar Terisi',
            'price_per_day' => 150000,
            'status' => 'terisi',
        ]);
        Service::query()->create([
            'type' => 'penitipan_addon',
            'name' => 'Mandi Saat Penitipan',
            'price' => 50000,
        ]);

        $response = $this->get(route('boarding.index'));

        $response->assertOk()
            ->assertViewIs('boarding.index')
            ->assertViewHas('rooms', fn ($rooms): bool => $rooms->contains('id', $availableRoom->id)
                && $rooms->count() === 1)
            ->assertDontSee('Mandi Saat Penitipan')
            ->assertSee('Rp100.000 / hari');
    }

    public function test_grooming_page_shows_only_active_grooming_services(): void
    {
        $activeService = Service::query()->create([
            'type' => 'grooming',
            'name' => 'Grooming kucing',
            'price' => 90000,
        ]);
        Service::query()->create([
            'type' => 'grooming',
            'name' => 'Grooming nonaktif',
            'price' => 0,
            'is_active' => false,
        ]);
        Service::query()->create([
            'type' => 'penitipan_addon',
            'name' => 'Mandi penitipan',
            'price' => 50000,
        ]);

        $response = $this->get(route('grooming.index'));

        $response->assertOk()
            ->assertViewIs('grooming.index')
            ->assertViewHas('services', fn ($services): bool => $services->contains('id', $activeService->id)
                && $services->count() === 1);
    }

    public function test_products_page_shows_active_products_with_their_categories(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Makanan Kucing',
            'slug' => 'makanan-kucing',
            'type' => 'makanan',
        ]);
        $product = Product::query()->create([
            'product_category_id' => $category->id,
            'sku' => 'CAT-FOOD-01',
            'name' => 'Makanan Kucing',
            'slug' => 'makanan-kucing-premium',
            'price' => 125000,
            'stock' => 8,
        ]);
        Product::query()->create([
            'product_category_id' => $category->id,
            'sku' => 'CAT-FOOD-02',
            'name' => 'Produk nonaktif',
            'slug' => 'produk-nonaktif',
            'price' => 1000,
            'is_active' => false,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertOk()
            ->assertViewIs('products.index')
            ->assertViewHas('products', function (LengthAwarePaginator $products) use ($product, $category): bool {
                $listedProduct = $products->getCollection()->first();

                return $products->count() === 1
                    && $listedProduct?->is($product)
                    && $listedProduct->relationLoaded('category')
                    && $listedProduct->category->is($category);
            });
    }
}
