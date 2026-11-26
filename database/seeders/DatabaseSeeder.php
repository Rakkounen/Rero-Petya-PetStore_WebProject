<?php

namespace Database\Seeders;

use App\Models\BoardingRoom;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $food = ProductCategory::query()->updateOrCreate(
            ['slug' => 'makanan-hewan'],
            [
                'name' => 'Makanan Hewan',
                'type' => 'makanan',
                'description' => 'Makanan bernutrisi untuk hewan peliharaan.',
            ],
        );

        $accessories = ProductCategory::query()->updateOrCreate(
            ['slug' => 'aksesoris-hewan'],
            [
                'name' => 'Aksesoris Hewan',
                'type' => 'aksesoris',
                'description' => 'Kebutuhan dan aksesoris hewan peliharaan.',
            ],
        );

        foreach ([
            [
                'name' => 'Grooming Reptil',
                'description' => 'Perawatan khusus yang disesuaikan untuk hewan reptil.',
                'pet_species' => 'reptil',
                'price' => 75000,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Grooming Kucing',
                'description' => 'Perawatan khusus untuk bulu dan kuku kucing.',
                'pet_species' => 'kucing',
                'price' => 85000,
                'duration_minutes' => 75,
            ],
            [
                'name' => 'Grooming Anjing Kecil',
                'description' => 'Perawatan lengkap untuk anjing berukuran kecil.',
                'pet_species' => 'anjing',
                'price' => 100000,
                'duration_minutes' => 90,
            ],
            [
                'name' => 'Grooming Anjing Besar',
                'description' => 'Perawatan bulu dan kuku untuk anjing berukuran besar.',
                'pet_species' => 'anjing',
                'price' => 150000,
                'duration_minutes' => 120,
            ],
            [
                'name' => 'Potong Kuku Hewan',
                'description' => 'Pemotongan kuku aman untuk hewan peliharaan.',
                'pet_species' => 'semua',
                'price' => 30000,
                'duration_minutes' => 30,
            ],
        ] as $service) {
            Service::query()->updateOrCreate(
                ['type' => 'grooming', 'name' => $service['name']],
                [...$service, 'type' => 'grooming', 'is_active' => true],
            );
        }

        Service::query()
            ->where('type', 'penitipan_addon')
            ->update(['is_active' => false]);

        BoardingRoom::query()->updateOrCreate(
            ['code' => 'CAT-STD'],
            [
                'name' => 'Penitipan Anjing & Kucing',
                'room_type' => 'standar',
                'pet_species' => 'anjing_kucing',
                'size' => 'sedang',
                'price_per_day' => 75000,
                'facilities' => 'Layanan penitipan yang menerima anjing dan kucing dengan tarif yang sama.',
                'status' => 'tersedia',
            ],
        );

        Product::query()->updateOrCreate(
            ['sku' => 'PETYA-FOOD-001'],
            [
                'product_category_id' => $food->id,
                'name' => 'Makanan Kucing Premium',
                'slug' => 'makanan-kucing-premium',
                'brand' => 'Rero Petya',
                'pet_species' => 'kucing',
                'description' => 'Makanan harian kucing dengan nutrisi seimbang.',
                'price' => 125000,
                'stock' => 20,
                'is_active' => true,
            ],
        );

        Product::query()->updateOrCreate(
            ['sku' => 'PETYA-ACC-001'],
            [
                'product_category_id' => $accessories->id,
                'name' => 'Kalung Hewan',
                'slug' => 'kalung-hewan',
                'brand' => 'Rero Petya',
                'pet_species' => 'semua',
                'description' => 'Kalung nyaman untuk hewan peliharaan.',
                'price' => 45000,
                'stock' => 15,
                'is_active' => true,
            ],
        );

        foreach ([
            [
                'product_category_id' => $food->id,
                'sku' => 'PETYA-FOOD-002',
                'name' => 'Makanan Anjing Dewasa',
                'slug' => 'makanan-anjing-dewasa',
                'brand' => 'Rero Petya',
                'pet_species' => 'anjing',
                'description' => 'Makanan lengkap untuk kebutuhan harian anjing dewasa.',
                'price' => 145000,
                'stock' => 18,
            ],
            [
                'product_category_id' => $accessories->id,
                'sku' => 'PETYA-ACC-002',
                'name' => 'Tali Jalan Hewan',
                'slug' => 'tali-jalan-hewan',
                'brand' => 'Rero Petya',
                'pet_species' => 'anjing',
                'description' => 'Tali jalan kuat dan nyaman digunakan.',
                'price' => 65000,
                'stock' => 12,
            ],
            [
                'product_category_id' => $accessories->id,
                'sku' => 'PETYA-ACC-003',
                'name' => 'Mainan Bola Kucing',
                'slug' => 'mainan-bola-kucing',
                'brand' => 'Rero Petya',
                'pet_species' => 'kucing',
                'description' => 'Mainan bola untuk menemani aktivitas kucing.',
                'price' => 25000,
                'stock' => 25,
            ],
        ] as $product) {
            Product::query()->updateOrCreate(
                ['sku' => $product['sku']],
                [...$product, 'is_active' => true],
            );
        }

        $adminUsername = config('app.admin_username');
        $adminPassword = config('app.admin_password');
        $admin = User::query()->where('username', $adminUsername)->first();

        if ($admin !== null) {
            if ($admin->role !== 'admin') {
                throw new RuntimeException("Username [{$adminUsername}] is already assigned to a non-admin user.");
            }
        } elseif (! is_string($adminPassword) || $adminPassword === '') {
            $this->command?->warn('Set ADMIN_PASSWORD in the environment to seed the admin account.');
        } else {
            $admin = User::query()->create([
                'name' => 'Administrator',
                'username' => $adminUsername,
                'email' => 'admin@reropetya.local',
                'password' => $adminPassword,
            ]);
            $admin->forceFill(['role' => 'admin'])->save();
        }
    }
}
