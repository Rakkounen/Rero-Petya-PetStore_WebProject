<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_seeder_creates_five_products_and_grooming_services_and_demo_admin(): void
    {
        config()->set('app.admin_username', 'admin');
        config()->set('app.admin_password', 'admin1');

        $this->seed();

        $this->assertDatabaseCount('products', 5);
        $this->assertDatabaseCount('services', 5);
        $this->assertDatabaseHas('services', [
            'type' => 'grooming',
            'name' => 'Grooming Reptil',
            'pet_species' => 'reptil',
        ]);
        $this->assertDatabaseMissing('services', [
            'type' => 'grooming',
            'name' => 'Grooming Dasar',
        ]);
        $this->assertDatabaseCount('boarding_rooms', 1);
        $this->assertDatabaseHas('boarding_rooms', [
            'code' => 'CAT-STD',
            'name' => 'Penitipan Anjing & Kucing',
            'pet_species' => 'anjing_kucing',
            'price_per_day' => 75000,
        ]);

        $admin = User::query()->where('username', 'admin')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('admin1', $admin->password));

        $this->post(route('login.store'), [
            'username' => 'admin',
            'password' => 'admin1',
        ])->assertRedirect(route('admin.dashboard'));

        $this->seed();

        $this->assertDatabaseCount('products', 5);
        $this->assertDatabaseCount('services', 5);
        $this->assertDatabaseMissing('services', [
            'type' => 'grooming',
            'name' => 'Grooming Dasar',
        ]);
        $this->assertDatabaseCount('boarding_rooms', 1);
    }
}
