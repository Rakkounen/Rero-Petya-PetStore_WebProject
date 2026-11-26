<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_can_register_and_the_role_is_always_customer(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Daftar akun')
            ->assertSee('Login');

        $this->post(route('register.store'), [
            'name' => 'Pelanggan Baru',
            'username' => 'pelangganbaru',
            'email' => 'pelanggan@example.test',
            'password' => 'password-rahasia',
            'password_confirmation' => 'password-rahasia',
            'role' => 'admin',
        ])->assertRedirect(route('services.index'));

        $user = User::query()->where('username', 'pelangganbaru')->firstOrFail();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'pelanggan',
        ]);
        $this->assertNotSame('password-rahasia', $user->password);
        $this->assertTrue(Hash::check('password-rahasia', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_public_can_log_in_with_username_without_getting_admin_access(): void
    {
        $user = User::factory()->create([
            'username' => 'pelangganlogin',
            'password' => 'password-rahasia',
        ]);

        $this->post(route('login.store'), [
            'username' => $user->username,
            'password' => 'password-rahasia',
        ])->assertRedirect(route('services.index'));

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_public_cannot_register_reserved_admin_username(): void
    {
        $this->from(route('register'))
            ->post(route('register.store'), [
                'name' => 'Impersonator',
                'username' => 'admin',
                'email' => 'impersonator@example.test',
                'password' => 'password-rahasia',
                'password_confirmation' => 'password-rahasia',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['username']);

        $this->assertDatabaseMissing('users', ['email' => 'impersonator@example.test']);
    }

    public function test_login_page_has_a_generic_label_and_username_field(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<h1 class="page-title">Login</h1>', false)
            ->assertSee('name="username"', false)
            ->assertDontSee('Login admin');
    }
}
