<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_can_be_rendered()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_forgot_password_link_visible_on_login_page()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Lupa password?');
        $response->assertSee(route('password.request'));
    }

    public function test_forgot_password_sends_reset_link_with_valid_email()
    {
        $user = User::factory()->create([
            'email' => 'test@superinventory.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'test@superinventory.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_forgot_password_fails_with_invalid_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@superinventory.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_forgot_password_validates_email_format()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_forgot_password_requires_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => '',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_authenticated_user_cannot_access_forgot_password_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('password.request'));

        $response->assertRedirect(route('dashboard'));
    }
}
