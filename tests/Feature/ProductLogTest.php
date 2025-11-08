<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductLogs;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;


class ProductLogTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_logs_when_a_product_is_deleted()
    {
        // create a user and a product
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Test Product']);

        $response = $this->actingAs($user)->deleteJson("/products/deleteProduct/{$product->id}");

        $response->assertStatus(200);

        $log = \App\Models\ProductLogs::latest()->first();
        dump($log->toArray());

        $this->assertDatabaseHas('product_logs', [
            'user_id' => $user->id,
            'product_id' => null, // <- harusnya null setelah delete
            'action' => 'delete',
            'description' => 'Hapus product ' . $product->name,
        ]);
    }

    #[Test]
    public function it_logs_when_a_product_is_updated()
    {
        // create a user and a product
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create([
            'supplier_id' => $supplier->id,
            'name' => 'Old Product',
            'category' => 'Drinks',
            'stock' => 5,
            'unit' => 'bottle',
        ]);

        $updatedData = [
            'name' => 'Updated Product',
            'stock' => 10,
        ];

        $response = $this->putJson("/products/updateProduct/{$product->id}", $updatedData);

        $response->assertStatus(200);

        $log = \App\Models\ProductLogs::latest()->first();
        dump($log->toArray());

        $this->assertDatabaseHas('product_logs', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => 'updated',
        ]);

        $log = ProductLogs::latest()->first();
        $this->assertStringContainsString('"stock":{"from":5,"to":10}', $log->description);
        $this->assertStringContainsString('"name":{"from":"Old Product","to":"Updated Product"}', $log->description);
    }

    #[Test]
    public function it_logs_when_a_product_is_created()
    {
        // create a user and a product
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();

        $this->actingAs($user);

        $data = [
            'supplier_id' => $supplier->id,
            'name' => 'New Test Product',
            'category' => 'Food',
            'stock' => 10,
            'unit' => 'pcs',
        ];

        $response = $this->postJson('/products/addProduct', $data);

        $response->assertStatus(201);

        $product = Product::where('name', 'New Test Product')->first();

        $log = \App\Models\ProductLogs::latest()->first();
        dump($log->toArray());

        $this->assertDatabaseHas('product_logs', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => 'created',
            'description' => 'Menambahkan product ' . $product->name,
        ]);
    }

    #[Test]
    public function it_returns_logs_matching_user_name()
    {
        $user = User::factory()->create(['name' => 'Alice']);
        $userMatch = User::factory()->create(['name' => 'John Doe']);
        $userNoMatch = User::factory()->create(['name' => 'Jane Smith']);
        $product = Product::factory()->create(['name' => 'Latte']);

        ProductLogs::factory()->create([
            'user_id' => $userMatch->id,
            'product_id' => $product->id,
            'action' => 'created',
        ]);

        ProductLogs::factory()->create([
            'user_id' => $userNoMatch->id,
            'product_id' => $product->id,
            'action' => 'updated',
        ]);
        $this->actingAs($user);
        $response = $this->get('/product-logs/search?search=John');

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    #[Test]
    public function it_returns_logs_matching_product_name()
    {
        $user = User::factory()->create(['name' => 'Alice']);
        $productMatch = Product::factory()->create(['name' => 'Milk Tea']);
        $productNoMatch = Product::factory()->create(['name' => 'Black Coffee']);

        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productMatch->id,
            'action' => 'created',
        ]);

        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productNoMatch->id,
            'action' => 'deleted',
        ]);
        $this->actingAs($user);
        $response = $this->get('/product-logs/search?search=Milk');

        $response->assertStatus(200);
        $response->assertSee('Milk Tea');
        $response->assertDontSee('Black Coffee');
    }

    #[Test]
    public function it_shows_all_logs_by_default()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        ProductLogs::factory()->count(3)->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->get('/stock-history');
        $response->assertStatus(200);
        $response->assertViewIs('productLogs.index');
        $response->assertSee($product->name);
    }

    #[Test]
    public function it_filters_logs_by_search_user_name()
    {
        $userMatch = User::factory()->create(['name' => 'John Doe']);
        $userNoMatch = User::factory()->create(['name' => 'Jane Smith']);
        $product = Product::factory()->create(['name' => 'Latte']);

        $this->actingAs($userMatch);

        ProductLogs::factory()->create([
            'user_id' => $userMatch->id,
            'product_id' => $product->id,
        ]);

        ProductLogs::factory()->create([
            'user_id' => $userNoMatch->id,
            'product_id' => $product->id,
        ]);

        $response = $this->get('/stock-history?search=John');

        $response->assertStatus(200);
        $response->assertSee('John Doe');
    }

    #[Test]
    public function it_filters_logs_by_search_product_name()
    {
        $user = User::factory()->create();
        $productMatch = Product::factory()->create(['name' => 'Milk Tea']);
        $productNoMatch = Product::factory()->create(['name' => 'Black Coffee']);

        $this->actingAs($user);

        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productMatch->id,
        ]);

        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productNoMatch->id,
        ]);

        $response = $this->get('/stock-history?search=Milk');

        $response->assertStatus(200);
        $response->assertSee('Milk Tea');
        $response->assertDontSee('Black Coffee');
    }

    #[Test]
    public function it_filters_logs_by_action()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => 'created',
        ]);

        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => 'deleted',
        ]);

        $response = $this->get('/stock-history?action=created');

        $response->assertStatus(200);
        $response->assertSee('created');
        $response->assertDontSeeText('deleted');
        $response->assertDontSeeText('updated');
    }

    #[Test]
    public function it_filters_logs_by_user_id()
    {
        $userMatch = User::factory()->create();
        $userNoMatch = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($userMatch);

        ProductLogs::factory()->create([
            'user_id' => $userMatch->id,
            'product_id' => $product->id,
        ]);

        ProductLogs::factory()->create([
            'user_id' => $userNoMatch->id,
            'product_id' => $product->id,
        ]);

        $response = $this->get("/stock-history?user_id={$userMatch->id}");

        $response->assertStatus(200);
        $response->assertSee($userMatch->name);
        $response->assertDontSee(['<td>' . $userNoMatch->name . '</td>']);
    }

    #[Test]
    public function it_filters_logs_by_date_range()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        // Log inside the range
        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'created_at' => now()->subDays(2),
        ]);

        // Log outside the range
        ProductLogs::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'created_at' => now()->subDays(10),
        ]);

        $start = now()->subDays(3)->toDateString();
        $end = now()->toDateString();

        $response = $this->get("/stock-history?start_date=$start&end_date=$end");

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }
}
