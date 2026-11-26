<?php

namespace Tests\Feature;

use App\Models\BoardingRoom;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class PublicCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_services_page_lists_active_services_and_filters_by_type(): void
    {
        $grooming = Service::query()->create([
            'type' => 'grooming',
            'name' => "Mandi <script>alert('xss')</script>",
            'price' => 75000,
        ]);
        Service::query()->create([
            'type' => 'penitipan_addon',
            'name' => 'Mandi Penitipan',
            'price' => 50000,
        ]);
        Service::query()->create([
            'type' => 'grooming',
            'name' => 'Grooming nonaktif',
            'price' => 0,
            'is_active' => false,
        ]);

        $response = $this->get(route('services.index', ['type' => 'grooming']));

        $response->assertOk()
            ->assertViewIs('services.index')
            ->assertViewHas('services', fn (LengthAwarePaginator $services): bool => $services->count() === 1
                && $services->first()?->is($grooming));

        $this->assertStringContainsString('&lt;script&gt;', $response->getContent());
        $this->assertStringNotContainsString("<script>alert('xss')</script>", $response->getContent());
    }

    public function test_public_about_page_shows_company_profile_and_is_linked_from_navigation(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertViewIs('about')
            ->assertSee('Tentang Kami')
            ->assertSee('Rero Petya adalah toko hewan peliharaan')
            ->assertSee('makanan dan aksesoris hewan')
            ->assertSee('grooming')
            ->assertSee('penitipan anjing dan kucing')
            ->assertSee(route('services.index'))
            ->assertSee(route('products.index'));
    }

    public function test_public_service_detail_is_available_only_for_active_services(): void
    {
        $activeService = Service::query()->create([
            'type' => 'grooming',
            'name' => 'Grooming',
            'price' => 75000,
        ]);
        $inactiveService = Service::query()->create([
            'type' => 'grooming',
            'name' => 'Layanan tersembunyi',
            'price' => 0,
            'is_active' => false,
        ]);
        $legacyBoardingAddon = Service::query()->create([
            'type' => 'penitipan_addon',
            'name' => 'Mandi Saat Penitipan',
            'price' => 50000,
        ]);

        $this->get(route('services.show', $activeService))
            ->assertOk()
            ->assertViewIs('services.show')
            ->assertViewHas('service', $activeService);

        $this->get(route('services.show', $inactiveService))->assertNotFound();
        $this->get(route('services.show', $legacyBoardingAddon))->assertNotFound();
    }

    public function test_public_grooming_catalog_identifies_the_reptile_service(): void
    {
        $service = Service::query()->create([
            'type' => 'grooming',
            'name' => 'Grooming Reptil',
            'description' => 'Perawatan khusus untuk reptil.',
            'pet_species' => 'reptil',
            'price' => 75000,
        ]);

        $this->get(route('services.index', ['type' => 'grooming']))
            ->assertOk()
            ->assertSee('Grooming Reptil')
            ->assertSee('Reptil')
            ->assertSee(route('services.show', $service));
    }

    public function test_public_service_catalog_shows_boarding_rooms_with_daily_rates_instead_of_add_ons(): void
    {
        $room = BoardingRoom::query()->create([
            'code' => 'ROOM-PUBLIC',
            'name' => 'Penitipan Standar',
            'room_type' => 'deluxe',
            'pet_species' => 'kucing',
            'price_per_day' => 75000,
            'facilities' => 'Area luas dan alas yang nyaman.',
        ]);
        BoardingRoom::query()->create([
            'code' => 'ROOM-FULL',
            'name' => 'Kamar sedang terisi',
            'price_per_day' => 100000,
            'status' => 'terisi',
        ]);
        Service::query()->create([
            'type' => 'penitipan_addon',
            'name' => 'Mandi Saat Penitipan',
            'price' => 50000,
        ]);

        $response = $this->get(route('services.index', ['type' => 'penitipan']));

        $response->assertOk()
            ->assertViewIs('services.index')
            ->assertViewHas('rooms', fn (LengthAwarePaginator $rooms): bool => $rooms->count() === 1
                && $rooms->first()?->is($room))
            ->assertSee('Rp75.000 / hari')
            ->assertSee(asset('images/logo-horizontal-kecil.png'))
            ->assertSee(route('services.boarding.show', $room))
            ->assertDontSee('Mandi Saat Penitipan');
    }

    public function test_public_boarding_room_detail_uses_the_room_code_and_hides_unavailable_rooms(): void
    {
        $room = BoardingRoom::query()->create([
            'code' => 'CAT-DELUXE',
            'name' => 'Penitipan Anjing & Kucing',
            'room_type' => 'deluxe',
            'pet_species' => 'anjing_kucing',
            'price_per_day' => 75000,
        ]);
        $unavailableRoom = BoardingRoom::query()->create([
            'code' => 'CAT-MAINT',
            'name' => 'Kamar perawatan',
            'price_per_day' => 50000,
            'status' => 'perawatan',
        ]);

        $this->get(route('services.boarding.show', $room))
            ->assertOk()
            ->assertViewIs('services.boarding-show')
            ->assertViewHas('room', $room)
            ->assertSee('Anjing & Kucing')
            ->assertSee('Rp75.000 / hari')
            ->assertSee('Biaya penitipan dihitung per hari sesuai jumlah hari layanan.')
            ->assertDontSee('kamar')
            ->assertDontSee('hotel')
            ->assertDontSee('tarif kamar');

        $this->get(route('services.boarding.show', $unavailableRoom))->assertNotFound();
    }

    public function test_public_product_detail_loads_category_and_hides_inactive_products(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Makanan Kucing',
            'slug' => 'makanan-kucing',
            'type' => 'makanan',
        ]);
        $product = Product::query()->create([
            'product_category_id' => $category->id,
            'sku' => 'CAT-DETAIL-01',
            'name' => 'Makanan Kucing',
            'slug' => 'makanan-kucing-detail',
            'description' => "<script>alert('xss')</script>",
            'price' => 125000,
            'stock' => 8,
        ]);
        $inactiveProduct = Product::query()->create([
            'product_category_id' => $category->id,
            'sku' => 'CAT-DETAIL-02',
            'name' => 'Produk nonaktif',
            'slug' => 'produk-nonaktif-detail',
            'price' => 1000,
            'is_active' => false,
        ]);

        $response = $this->get(route('products.show', $product))
            ->assertOk()
            ->assertViewIs('products.show')
            ->assertViewHas('product', fn (Product $listedProduct): bool => $listedProduct->is($product)
                && $listedProduct->relationLoaded('category')
                && $listedProduct->category->is($category));

        $this->assertStringContainsString('&lt;script&gt;', $response->getContent());
        $this->assertStringNotContainsString("<script>alert('xss')</script>", $response->getContent());

        $this->get(route('products.show', $inactiveProduct))->assertNotFound();
    }
}
