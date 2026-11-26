<?php

namespace Tests\Feature;

use App\Models\BoardingRoom;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    #[DataProvider('nonAdminRoles')]
    public function test_non_admin_user_is_forbidden_from_admin_pages(string $role): void
    {
        $user = User::factory()->create();
        $user->forceFill(['role' => $role])->save();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public static function nonAdminRoles(): array
    {
        return [
            'cashier' => ['kasir'],
            'groomer' => ['groomer'],
            'boarding staff' => ['staff_penitipan'],
        ];
    }

    public function test_admin_can_log_in_and_open_the_dashboard(): void
    {
        $admin = $this->createAdminUser([
            'username' => 'admin',
            'email' => 'admin@example.test',
        ]);

        $this->post(route('login.store'), [
            'username' => $admin->username,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewIs('admin.dashboard');
    }

    public function test_admin_can_create_update_and_delete_a_service(): void
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);

        $this->post(route('admin.services.store'), [
            'type' => 'grooming',
            'name' => 'Grooming Kucing',
            'description' => 'Mandi dan sisir bulu.',
            'pet_species' => 'kucing',
            'price' => 80000,
            'duration_minutes' => 60,
            'is_active' => '1',
        ])->assertRedirect(route('admin.services.index'));

        $service = Service::query()->where('name', 'Grooming Kucing')->firstOrFail();
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'type' => 'grooming',
            'price' => 80000,
            'is_active' => true,
        ]);

        $this->put(route('admin.services.update', $service), [
            'type' => 'grooming',
            'name' => 'Grooming Kucing Premium',
            'description' => 'Grooming lengkap.',
            'pet_species' => 'kucing',
            'price' => 100000,
            'duration_minutes' => 75,
            'is_active' => '1',
        ])->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Grooming Kucing Premium',
            'price' => 100000,
        ]);

        $this->delete(route('admin.services.destroy', $service))
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_admin_can_create_update_and_remove_a_product_image(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();
        $category = ProductCategory::query()->create([
            'name' => 'Makanan',
            'slug' => 'makanan',
            'type' => 'makanan',
        ]);
        $this->actingAs($admin);

        $this->post(route('admin.products.store'), [
            'product_category_id' => $category->id,
            'sku' => 'FOOD-001',
            'name' => 'Makanan Premium',
            'slug' => 'makanan-premium',
            'pet_species' => 'kucing',
            'cost_price' => 50000,
            'price' => 80000,
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => '1',
            'image' => UploadedFile::fake()->createWithContent(
                'makanan.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADUlEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC'),
            ),
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('sku', 'FOOD-001')->firstOrFail();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'slug' => 'makanan-premium',
            'price' => 80000,
        ]);
        $this->assertStringStartsWith('products/', $product->image);
        Storage::disk('public')->assertExists($product->image);

        $this->put(route('admin.products.update', $product), [
            'product_category_id' => $category->id,
            'sku' => $product->sku,
            'name' => 'Makanan Premium Baru',
            'slug' => $product->slug,
            'pet_species' => 'kucing',
            'cost_price' => 55000,
            'price' => 90000,
            'stock' => 8,
            'min_stock' => 2,
            'is_active' => '1',
            'remove_image' => '1',
        ])->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame('Makanan Premium Baru', $product->name);
        $this->assertNull($product->image);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'price' => 90000]);

        $this->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_admin_can_open_catalog_create_and_edit_forms(): void
    {
        $admin = $this->createAdminUser();
        $category = ProductCategory::query()->create([
            'name' => 'Aksesoris',
            'slug' => 'aksesoris-form',
            'type' => 'aksesoris',
        ]);
        $product = Product::query()->create([
            'product_category_id' => $category->id,
            'sku' => 'FORM-001',
            'name' => 'Kalung',
            'slug' => 'kalung-form',
            'price' => 20000,
        ]);
        $service = Service::query()->create([
            'type' => 'grooming',
            'name' => 'Mandi',
            'price' => 50000,
        ]);
        $room = BoardingRoom::query()->create([
            'code' => 'ROOM-FORM',
            'name' => 'Kamar Form',
            'price_per_day' => 50000,
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.boarding-rooms.create'))->assertOk()->assertViewIs('admin.boarding-rooms.create');
        $this->get(route('admin.boarding-rooms.edit', $room))->assertOk()->assertViewIs('admin.boarding-rooms.edit');
        $this->get(route('admin.services.create'))->assertOk()->assertViewIs('admin.services.create');
        $this->get(route('admin.services.edit', $service))->assertOk()->assertViewIs('admin.services.edit');
        $this->get(route('admin.products.create'))->assertOk()->assertViewIs('admin.products.create');
        $this->get(route('admin.products.edit', $product))->assertOk()->assertViewIs('admin.products.edit');
    }

    public function test_invalid_service_input_returns_a_field_error_without_creating_data(): void
    {
        $this->actingAs($this->createAdminUser())
            ->from(route('admin.services.create'))
            ->post(route('admin.services.store'), [
                'type' => 'grooming',
                'pet_species' => 'kucing',
                'price' => 50000,
            ])
            ->assertRedirect(route('admin.services.create'))
            ->assertSessionHasErrors(['name' => 'The name field is required.']);

        $this->assertDatabaseCount('services', 0);
    }

    public function test_admin_can_create_update_and_delete_a_daily_boarding_room(): void
    {
        $this->actingAs($this->createAdminUser());

        $this->post(route('admin.boarding-rooms.store'), [
            'code' => 'CAT-VIP',
            'name' => 'Penitipan Anjing & Kucing',
            'pet_species' => 'anjing_kucing',
            'price_per_day' => 175000,
            'facilities' => 'Kamar pribadi dan tempat tidur.',
            'status' => 'tersedia',
        ])->assertRedirect(route('admin.boarding-rooms.index'));

        $room = BoardingRoom::query()->where('code', 'CAT-VIP')->firstOrFail();
        $this->assertDatabaseHas('boarding_rooms', [
            'id' => $room->id,
            'price_per_day' => 175000,
            'status' => 'tersedia',
        ]);

        $this->put(route('admin.boarding-rooms.update', $room), [
            'code' => $room->code,
            'name' => 'Penitipan Anjing & Kucing Plus',
            'pet_species' => 'anjing_kucing',
            'price_per_day' => 200000,
            'facilities' => 'Kamar pribadi dan tempat tidur.',
            'status' => 'tersedia',
        ])->assertRedirect(route('admin.boarding-rooms.index'));

        $this->assertDatabaseHas('boarding_rooms', [
            'id' => $room->id,
            'name' => 'Penitipan Anjing & Kucing Plus',
            'price_per_day' => 200000,
        ]);

        $this->delete(route('admin.boarding-rooms.destroy', $room))
            ->assertRedirect(route('admin.boarding-rooms.index'));

        $this->assertDatabaseMissing('boarding_rooms', ['id' => $room->id]);
    }

    public function test_deleting_a_sold_product_deactivates_it_and_preserves_order_history(): void
    {
        $admin = $this->createAdminUser();
        $category = ProductCategory::query()->create([
            'name' => 'Makanan',
            'slug' => 'makanan-riwayat',
            'type' => 'makanan',
        ]);
        $product = Product::query()->create([
            'product_category_id' => $category->id,
            'sku' => 'FOOD-HISTORY',
            'name' => 'Produk Terjual',
            'slug' => 'produk-terjual',
            'price' => 10000,
        ]);
        $order = Order::query()->create([
            'order_number' => 'ORD-TEST-001',
            'subtotal' => 10000,
            'total' => 10000,
        ]);
        $orderItem = OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 1,
            'price' => 10000,
            'subtotal' => 10000,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'is_active' => false]);
        $this->assertDatabaseHas('order_items', ['id' => $orderItem->id]);
    }

    /**
     * @param  array<string, string>  $attributes
     */
    private function createAdminUser(array $attributes = []): User
    {
        $admin = User::factory()->create($attributes);
        $admin->forceFill(['role' => 'admin'])->save();

        return $admin;
    }
}
