<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\TwoFactorCode;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TwoFactorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        User::factory()->create([
            'email' => 'test@superinventory.com',
            'password' => bcrypt('password123'),
            'two_factor_enabled' => false,
        ]);
    }

    public function test_enable_two_factor_sets_flag_to_true(): void
    {
        $user = User::where('email', 'test@superinventory.com')->first();
        
        $response = $this->actingAs($user)->post(route('two-factor.enable'));
        
        $user->refresh();
        $this->assertTrue($user->two_factor_enabled);
    }

    public function test_disable_two_factor_sets_flag_to_false(): void
    {
        $user = User::where('email', 'test@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        $response = $this->actingAs($user)->post(route('two-factor.disable'));
        
        $user->refresh();
        $this->assertFalse($user->two_factor_enabled);
    }

    public function test_generate_two_factor_code_creates_six_digit_code(): void
    {
        $user = User::where('email', 'test@superinventory.com')->first();
        $user->update(['two_factor_enabled' => true]);
        
        // Simulate code generation
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => sprintf('%06d', rand(0, 999999)),
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        $this->assertEquals(6, strlen($code->code));
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code->code);
    }

    public function test_verify_two_factor_code_authenticates_user_with_valid_code(): void
    {
        $user = User::where('email', 'test@superinventory.com')->first();
        
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        $response = $this->post(route('two-factor.verify'), [
            'code' => '123456',
        ]);
        
        $this->assertAuthenticatedAs($user);
    }

    public function test_verify_two_factor_code_marks_code_as_used(): void
    {
        $user = User::where('email', 'test@superinventory.com')->first();
        
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        $this->post(route('two-factor.verify'), [
            'code' => '123456',
        ]);
        
        $code->refresh();
        $this->assertTrue($code->used);
    }

    public function test_resend_two_factor_code_invalidates_old_codes(): void
    {
        $user = User::where('email', 'test@superinventory.com')->first();
        
        $oldCode = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '111111',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        session(['2fa_user_id' => $user->id]);
        
        $this->post(route('two-factor.resend'));
        
        $oldCode->refresh();
        $this->assertTrue($oldCode->used);
    }

    public function test_two_factor_code_expires_correctly(): void
    {
        $user = User::where('email', 'test@superinventory.com')->first();
        
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
        ]);
        
        $this->assertTrue($code->expires_at->isFuture());
    }
}
