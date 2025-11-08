<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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

    public function test_index_view_loads_successfully()
    {
        View::addLocation(resource_path('views')); // ensure view path exists

        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    public function test_it_can_fetch_all_products()
    {
        Product::factory()->count(3)->create(['supplier_id' => $this->supplier->id]);

        $response = $this->getJson('/products/getallproduct');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'dataProduct', 'kategori']);
    }

    public function test_get_products_returns_404_if_empty()
    {
        Product::query()->delete();

        $response = $this->getJson('/products/getallproduct');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Tidak ada product']);
    }


    public function test_it_can_insert_a_product()
    {
        $payload = [
            'supplier_id' => $this->supplier->id,
            'name' => 'Keyboard Gaming',
            'category' => 'Peripherals',
            'stock' => 10,
            'unit' => 'pcs',
        ];

        $response = $this->postJson('/products/addProduct/', $payload);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Berhasil Menambahkan Product']);
    }

    public function test_insert_product_validation_fails()
    {
        $payload = [
            'supplier_id' => null,
            'name' => '',
            'stock' => -1,
        ];

        $response = $this->postJson('/products/addProduct/', $payload);
        $response->assertStatus(422);
    }

    public function test_it_can_fetch_product_by_id()
    {
        $product = Product::factory()->create(['supplier_id' => $this->supplier->id]);

        $response = $this->getJson('/products/getProductById?id=' . $product->id);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Berhasil Fetch Detail Product']);
    }

    public function test_get_product_by_id_returns_404_if_not_found()
    {
        $response = $this->getJson('/products/getProductById?id=99999');
        $response->assertStatus(404)
            ->assertJson(['message' => 'Product tidak ditemukan']);
    }

    public function test_it_can_update_a_product()
    {
        $product = Product::factory()->create(['supplier_id' => $this->supplier->id]);

        $response = $this->putJson('/products/updateProduct/' . $product->id, [
            'name' => 'Updated Keyboard',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Berhasil Update Product']);
    }

    public function test_update_product_returns_404_if_not_found()
    {
        $response = $this->putJson('/products/updateProduct/99999', [
            'name' => 'Does Not Exist',
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Product tidak ditemukan']);
    }

    public function test_update_product_validation_fails()
    {
        $product = Product::factory()->create(['supplier_id' => $this->supplier->id]);

        $response = $this->putJson('/products/updateProduct/' . $product->id, [
            'stock' => -5,
        ]);

        $response->assertStatus(422);
    }

    public function test_it_can_delete_a_product()
    {
        $product = Product::factory()->create(['supplier_id' => $this->supplier->id]);

        $response = $this->deleteJson('/products/deleteProduct/' . $product->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Berhasil Hapus Product']);
    }

    public function test_delete_product_returns_404_if_not_found()
    {
        $response = $this->deleteJson('/products/deleteProduct/99999');
        $response->assertStatus(404)
            ->assertJson(['message' => 'Product tidak ditemukan']);
    }

    public function test_it_can_fetch_all_suppliers()
    {
        Supplier::factory()->count(2)->create();

        $response = $this->getJson('/products/getSuppliers');

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'dataSuppliers']);
    }

    public function test_get_suppliers_returns_404_if_none_exist()
    {
        Supplier::query()->delete();

        $response = $this->getJson('/products/getSuppliers');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Suppliers tidak ditemukan']);
    }


    public function test_insert_product_handles_exception()
    {
        $mock = $this->partialMock(Product::class, function ($mock) {
            $mock->shouldReceive('create')->andThrow(new \Exception('DB Error'));
        });

        $payload = [
            'supplier_id' => $this->supplier->id,
            'name' => 'Crash Keyboard',
            'stock' => 10,
        ];

        $response = $this->postJson('/products/addProduct/', $payload);
        $response->assertStatus(500)
            ->assertJson(['message' => 'Gagal Menambahkan Product']);
    }

    public function test_get_products_handles_exception()
    {
        $this->mock(Product::class, function ($mock) {
            $mock->shouldReceive('with')->andThrow(new \Exception('Fetch Error'));
        });

        $response = $this->getJson('/products/getallproduct');
        $response->assertStatus(500)
            ->assertJson(['message' => 'Gagal Get Tahun Ajaran']);
    }
}
