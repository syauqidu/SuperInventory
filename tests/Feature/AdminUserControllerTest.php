<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin()
    {
        return User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'approved' => true,
        ]);
    }

    public function test_admin_can_view_pending_users()
    {
        $admin = $this->createAdmin();

        // create pending and approved users
        $pending = User::factory()->create([
            'email' => 'pending@example.com',
            'approved' => false,
            'role' => 'staff',
        ]);

        User::factory()->create([
            'email' => 'approved@example.com',
            'approved' => true,
            'role' => 'staff',
        ]);

        $resp = $this->actingAs($admin)->get(route('admin.users.index'));
        $resp->assertStatus(200);
        $resp->assertSee('pending@example.com');
        $resp->assertDontSee('approved@example.com');
    }

    public function test_non_admin_cannot_access_admin_pages()
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'approved' => true,
        ]);

        $resp = $this->actingAs($user)->get(route('admin.users.index'));
        $resp->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $admin = $this->createAdmin();

        $resp = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'staff',
            // do not set approved -> should default to false
        ]);

        $resp->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'role' => 'staff',
            'approved' => false,
        ]);

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_admin_validation_on_create()
    {
        $admin = $this->createAdmin();

        $resp = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '',
            'role' => 'invalid',
        ]);

        $resp->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_admin_can_edit_update_user()
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'name' => 'Old',
            'email' => 'old@example.com',
            'role' => 'staff',
            'approved' => false,
        ]);

        $resp = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'Updated',
            'email' => 'updated@example.com',
            'role' => 'admin',
            'password' => '',
            'password_confirmation' => '',
            'approved' => '1',
        ]);

        $resp->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated',
            'email' => 'updated@example.com',
            'role' => 'admin',
            'approved' => true,
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $resp = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));
        $resp->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_can_approve_user()
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create([
            'approved' => false,
            'role' => 'staff',
        ]);

        $resp = $this->actingAs($admin)->post(route('admin.users.approve', $user));
        $resp->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'approved' => true,
        ]);
    }
}
