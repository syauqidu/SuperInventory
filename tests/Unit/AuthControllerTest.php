<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'testadmin@superinventory.com',
            'password' => bcrypt('testpass123'),
            'role' => 'admin'
        ]);
    }

    public function test_show_login_returns_view(): void
    {
        $response = $this->get(route('login'));
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_authenticates_user(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'testadmin@superinventory.com',
            'password' => 'testpass123',
        ]);
        
        $this->assertTrue(Auth::check());
        $this->assertEquals('testadmin@superinventory.com', Auth::user()->email);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'testadmin@superinventory.com',
            'password' => 'wrongpassword',
        ]);
        
        $this->assertFalse(Auth::check());
        $response->assertSessionHasErrors();
    }

    public function test_logout_logs_out_user(): void
    {
        $user = User::where('email', 'testadmin@superinventory.com')->first();
        Auth::login($user);
        
        $this->assertTrue(Auth::check());
        
        $response = $this->post(route('logout'));
        
        $this->assertFalse(Auth::check());
        $response->assertRedirect(route('login'));
    }

    public function test_login_redirects_authenticated_users(): void
    {
        $user = User::where('email', 'testadmin@superinventory.com')->first();
        
        $response = $this->actingAs($user)->get(route('login'));
        
        $response->assertRedirect('/dashboard');
    }
}
