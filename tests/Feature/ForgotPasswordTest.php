<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'testuser@superinventory.com',
            'password' => bcrypt('password123'),
            'role' => 'staff'
        ]);
    }

    public function test_forgot_password_page_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_forgot_password_link_visible_on_login_page(): void
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertSee('Lupa Password?');
    }

    public function test_forgot_password_sends_reset_link_with_valid_email(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'testuser@superinventory.com',
        ]);
        
        $response->assertSessionHas('success');
        $response->assertRedirect(route('login'));
        
        // Check if password reset token was created
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'testuser@superinventory.com',
        ]);
    }

    public function test_forgot_password_fails_with_invalid_email(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'nonexistent@superinventory.com',
        ]);
        
        $response->assertSessionHasErrors(['email']);
    }

    public function test_forgot_password_validates_email_format(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'invalid-email',
        ]);
        
        $response->assertSessionHasErrors(['email']);
    }

    public function test_forgot_password_requires_email(): void
    {
        $response = $this->post('/forgot-password', []);
        
        $response->assertSessionHasErrors(['email']);
    }

    public function test_reset_password_page_can_be_rendered_with_valid_token(): void
    {
        // Create password reset token
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'testuser@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $response = $this->get("/reset-password/{$token}?email=testuser@superinventory.com");
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
    }

    public function test_reset_password_page_fails_with_invalid_token(): void
    {
        $response = $this->get("/reset-password/invalid-token?email=testuser@superinventory.com");
        
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'testuser@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'testuser@superinventory.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        
        // Verify user can login with new password
        $this->assertTrue(
            Auth::attempt([
                'email' => 'testuser@superinventory.com',
                'password' => 'newpassword123'
            ])
        );
    }

    public function test_reset_password_fails_with_mismatched_passwords(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'testuser@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'testuser@superinventory.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);
        
        $response->assertSessionHasErrors(['password']);
    }

    public function test_reset_password_requires_minimum_password_length(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'testuser@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => 'testuser@superinventory.com',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);
        
        $response->assertSessionHasErrors(['password']);
    }

    public function test_reset_password_token_is_deleted_after_successful_reset(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'testuser@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $this->post('/reset-password', [
            'token' => $token,
            'email' => 'testuser@superinventory.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'testuser@superinventory.com',
        ]);
    }

    public function test_expired_reset_token_cannot_be_used(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'testuser@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now()->subHours(2), // Expired (assuming 1 hour expiry)
        ]);
        
        $response = $this->followingRedirects()->post('/reset-password', [
            'token' => $token,
            'email' => 'testuser@superinventory.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        $response->assertStatus(200);
        $response->assertSee('Token reset password sudah kadaluarsa');
    }

    public function test_authenticated_user_cannot_access_forgot_password_page(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        
        $response = $this->actingAs($user)->get('/forgot-password');
        
        $response->assertRedirect('/dashboard');
    }
}
