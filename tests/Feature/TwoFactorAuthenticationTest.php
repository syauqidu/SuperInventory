<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TwoFactorCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'testuser@superinventory.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'two_factor_enabled' => false,
        ]);
    }

    public function test_two_factor_settings_page_can_be_accessed(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        
        $response = $this->actingAs($user)->get('/two-factor/settings');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.two-factor-settings');
    }

    public function test_user_can_enable_two_factor_authentication(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        
        $response = $this->actingAs($user)->post('/two-factor/enable');
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@superinventory.com',
            'two_factor_enabled' => true,
        ]);
    }

    public function test_user_can_disable_two_factor_authentication(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        $response = $this->actingAs($user)->post('/two-factor/disable');
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@superinventory.com',
            'two_factor_enabled' => false,
        ]);
    }

    public function test_two_factor_code_is_generated_on_login_when_enabled(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        $response = $this->post('/login', [
            'email' => 'testuser@superinventory.com',
            'password' => 'password123',
        ]);
        
        // User should not be fully authenticated yet
        $this->assertGuest();
        
        // Should redirect to 2FA verification page
        $response->assertRedirect('/two-factor/verify');
        
        // Check if 2FA code was created
        $this->assertDatabaseHas('two_factor_codes', [
            'user_id' => $user->id,
            'used' => false,
        ]);
    }

    public function test_two_factor_verification_page_can_be_rendered(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        // Store user ID in session (simulating partial login)
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->get('/two-factor/verify');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.two-factor-verify');
    }

    public function test_two_factor_code_has_correct_format(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        $this->post('/login', [
            'email' => 'testuser@superinventory.com',
            'password' => 'password123',
        ]);
        
        $code = TwoFactorCode::where('user_id', $user->id)->latest()->first();
        
        $this->assertNotNull($code);
        $this->assertEquals(6, strlen($code->code));
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code->code);
    }

    public function test_user_can_verify_with_correct_two_factor_code(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        // Generate 2FA code
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post('/two-factor/verify', [
            'code' => '123456',
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
        
        // Code should be marked as used
        $this->assertDatabaseHas('two_factor_codes', [
            'id' => $code->id,
            'used' => true,
        ]);
    }

    public function test_verification_fails_with_incorrect_two_factor_code(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post('/two-factor/verify', [
            'code' => '999999', // Wrong code
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors(['code']);
    }

    public function test_verification_fails_with_expired_two_factor_code(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->subMinutes(1), // Expired
            'used' => false,
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post('/two-factor/verify', [
            'code' => '123456',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors(['code']);
    }

    public function test_used_two_factor_code_cannot_be_reused(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => true, // Already used
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post('/two-factor/verify', [
            'code' => '123456',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors(['code']);
    }

    public function test_user_can_request_new_two_factor_code(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post('/two-factor/resend');
        
        $response->assertRedirect('/two-factor/verify');
        $response->assertSessionHas('success');
        
        // Check if new code was created
        $this->assertDatabaseHas('two_factor_codes', [
            'user_id' => $user->id,
            'used' => false,
        ]);
    }

    public function test_two_factor_code_resend_has_rate_limiting(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        session(['2fa_user_id' => $user->id]);
        
        // First request should succeed
        $response1 = $this->post('/two-factor/resend');
        $response1->assertSessionHas('success');
        
        // Immediate second request should fail (rate limited)
        $response2 = $this->post('/two-factor/resend');
        $response2->assertSessionHasErrors(['rate_limit']);
    }

    public function test_two_factor_verification_requires_code(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post('/two-factor/verify', []);
        
        $response->assertSessionHasErrors(['code']);
    }

    public function test_two_factor_code_must_be_six_digits(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post('/two-factor/verify', [
            'code' => '12345', // Only 5 digits
        ]);
        
        $response->assertSessionHasErrors(['code']);
    }

    public function test_guest_cannot_access_two_factor_settings(): void
    {
        $response = $this->get('/two-factor/settings');
        
        $response->assertRedirect(route('login'));
    }

    public function test_two_factor_code_expires_after_5_minutes(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        $this->assertTrue($code->expires_at->greaterThan(now()));
        $this->assertTrue($code->expires_at->lessThanOrEqualTo(now()->addMinutes(5)));
    }

    public function test_old_two_factor_codes_are_invalidated_when_new_code_is_generated(): void
    {
        $user = User::where('email', 'testuser@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        // Create old code
        $oldCode = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '111111',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        // Request new code
        $this->post('/two-factor/resend');
        
        // Old code should be marked as used/invalidated
        $this->assertDatabaseHas('two_factor_codes', [
            'id' => $oldCode->id,
            'used' => true,
        ]);
    }
}
