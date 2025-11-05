<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_register_page()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    public function test_user_can_register_and_account_requires_approval()
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Registrasi berhasil. Akun Anda menunggu persetujuan admin sebelum bisa digunakan.');

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'role' => 'staff',
            'approved' => false,
        ]);

        $user = User::where('email', 'testuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('secret123', $user->password));

        $this->assertGuest();
    }

    public function test_registration_validation_errors()
    {
        $response = $this->post(route('register'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }
}
