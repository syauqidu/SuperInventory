<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@superinventory.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);
        
        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@superinventory.com',
            'password' => bcrypt('password123'),
            'role' => 'staff'
        ]);
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@superinventory.com',
            'password' => 'password123',
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_login_with_invalid_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'wrong@email.com',
            'password' => 'password123',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    public function test_login_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@superinventory.com',
            'password' => 'wrongpassword',
        ]);
        
        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    public function test_login_with_remember_me(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@superinventory.com',
            'password' => 'password123',
            'remember' => true,
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
        
        // Check remember me cookie
        $this->assertNotNull(auth()->user()->getRememberToken());
    }

    public function test_authenticated_user_cannot_access_login_page(): void
    {
        $user = User::where('email', 'admin@superinventory.com')->first();
        
        $response = $this->actingAs($user)->get('/login');
        
        $response->assertRedirect('/dashboard');
    }

    public function test_logout_functionality(): void
    {
        $user = User::where('email', 'admin@superinventory.com')->first();
        
        $this->actingAs($user);
        $this->assertAuthenticated();
        
        $response = $this->post('/logout');
        
        $this->assertGuest();
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $user = User::where('email', 'admin@superinventory.com')->first();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertSee('Admin User');
    }

    public function test_staff_can_access_dashboard(): void
    {
        $user = User::where('email', 'staff@superinventory.com')->first();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertSee('Staff User');
    }

    public function test_login_validation_requires_email(): void
    {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);
        
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_validation_requires_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@superinventory.com',
        ]);
        
        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_validation_requires_valid_email_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);
        
        $response->assertSessionHasErrors(['email']);
    }
}
