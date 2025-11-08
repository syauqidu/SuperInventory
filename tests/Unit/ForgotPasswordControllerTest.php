<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        User::factory()->create([
            'email' => 'test@superinventory.com',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_show_forgot_password_form_returns_view(): void
    {
        $response = $this->get(route('password.request'));
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_send_reset_link_creates_token(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'test@superinventory.com',
        ]);
        
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@superinventory.com',
        ]);
    }

    public function test_send_reset_link_validates_email_exists(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@superinventory.com',
        ]);
        
        $response->assertSessionHasErrors(['email']);
    }

    public function test_show_reset_form_with_valid_token(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $response = $this->get(route('password.reset', ['token' => $token]) . '?email=test@superinventory.com');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
    }

    public function test_reset_password_updates_user_password(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@superinventory.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        $user = User::where('email', 'test@superinventory.com')->first();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_reset_password_deletes_token_after_success(): void
    {
        $token = \Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@superinventory.com',
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);
        
        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'test@superinventory.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@superinventory.com',
        ]);
    }
}
