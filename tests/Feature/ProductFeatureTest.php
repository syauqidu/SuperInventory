<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->supplier = Supplier::factory()->create();
    }

    #[Test]
    public function it_can_fetch_all_products()
    {
        Product::factory()->count(3)->create(['supplier_id' => $this->supplier->id]);

        $response = $this->getJson('/products/getallproduct'); // Adjust to your route

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'dataProduct',
                'kategori',
            ]);
    }

    #[Test]
    public function it_can_insert_a_product()
    {
        $payload = [
            'supplier_id' => $this->supplier->id,
            'name' => 'Keyboard Gaming',
            'category' => 'Peripherals',
            'stock' => 10,
            'unit' => 'pcs',
        ];

        $response = $this->postJson('/products/addProduct/', $payload); // adjust route name

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Berhasil Menambahkan Product',
            ]);

        $this->assertDatabaseHas('products', ['name' => 'Keyboard Gaming']);
    }

    #[Test]
    public function it_can_fetch_product_by_id()
    {
        $product = Product::factory()->create(['supplier_id' => $this->supplier->id]);

        $response = $this->getJson('/products/getProductById?id=' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Berhasil Fetch Detail Product',
            ]);
    }

    #[Test]
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create(['supplier_id' => $this->supplier->id]);

        $response = $this->putJson('/products/updateProduct/' . $product->id, [
            'name' => 'Updated Keyboard',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Berhasil Update Product',
            ]);

        $this->assertDatabaseHas('products', ['name' => 'Updated Keyboard']);
    }

    #[Test]
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create(['supplier_id' => $this->supplier->id]);

        $response = $this->deleteJson('/products/deleteProduct/' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Berhasil Hapus Product',
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    #[Test]
    public function it_can_fetch_all_suppliers()
    {
        Supplier::factory()->count(2)->create();

        $response = $this->getJson('/products/getSuppliers'); // adjust route

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'dataSuppliers',
            ]);
    }
}
