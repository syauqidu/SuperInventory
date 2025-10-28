<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class SupplierFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsUser()
    {
        $user = User::factory()->create();
        return $this->actingAs($user);
    }

    // #[Test]
    // public function guests_are_redirected_to_login_when_accessing_supplier_routes()
    // {
    //     $this->get('/suppliers')->assertRedirect('/login');
    // }

    #[Test]
    public function it_can_create_a_supplier_with_valid_data()
    {
        $this->actingAsUser();

        $response = $this->post("/suppliers", [
            "name" => "PT Sumber Rejeki",
            "contact" => "08123456789",
            "address" => "Bandung, Indonesia",
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas("suppliers", ["name" => "PT Sumber Rejeki"]);
    }

    #[Test]
    public function it_shows_validation_errors_when_creating_supplier_with_invalid_data()
    {
        $this->actingAsUser();

        $response = $this->post("/suppliers", [
            "name" => "",
            "contact" => "", // Trigger required rule
        ]);

        $response->assertSessionHasErrors(["name", "contact"]);
    }

    #[Test]
    public function index_displays_existing_suppliers()
    {
        $this->actingAsUser();

        $suppliers = Supplier::factory()->count(3)->create();

        $response = $this->get("/suppliers");

        $response->assertStatus(200);
        foreach ($suppliers as $supplier) {
            $response->assertSeeText($supplier->name);
        }
    }

    // #[Test]
    // public function it_can_display_supplier_with_its_products()
    // {
    //     $this->actingAsUser();

    //     $supplier = Supplier::factory()->has(Product::factory()->count(2))->create();

    //     $response = $this->get("/suppliers/{$supplier->id}");

    //     $response->assertStatus(200);
    //     $response->assertSee(e($supplier->name));
    //     foreach ($supplier->products as $p) {
    //         $response->assertSee(e($p->name));
    //     }
    // }

    #[Test]
    public function it_can_update_supplier()
    {
        $this->actingAsUser();

        $supplier = Supplier::factory()->create([
            "name" => "Old Name",
            "contact" => "0811111111",
        ]);

        $response = $this->put("/suppliers/{$supplier->id}", [
            "name" => "New Name",
            "contact" => "0822222222",
            "address" => "New Address",
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas("suppliers", [
            "id" => $supplier->id,
            "name" => "New Name",
        ]);
    }

    #[Test]
    public function it_can_delete_supplier_and_cascade_products()
    {
        $this->actingAsUser();

        // Ensure factory has relationship method hasProducts or use manual product creation
        $supplier = Supplier::factory()->create();
        Product::factory()
            ->count(3)
            ->create(["supplier_id" => $supplier->id]);

        $this->delete("/suppliers/{$supplier->id}");

        $this->assertDatabaseMissing("suppliers", ["id" => $supplier->id]);
        $this->assertDatabaseMissing("products", [
            "supplier_id" => $supplier->id,
        ]);
    }
    #[Test]
    public function index_displays_empty_message_when_no_suppliers_exist()
    {
        $this->actingAsUser();

        $response = $this->get("/suppliers");

        $response->assertStatus(200);
        $response->assertSeeText("Tidak ada data supplier.");
    }
}
