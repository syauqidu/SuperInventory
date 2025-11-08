<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_supplier()
    {
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['supplier_id' => $supplier->id]);

        $this->assertInstanceOf(Supplier::class, $product->supplier);
        $this->assertEquals($supplier->id, $product->supplier->id);
    }

    /** @test */
    public function it_has_many_stock_histories()
    {
        $product = Product::factory()->create();
        $histories = StockHistory::factory()->count(3)->create(['product_id' => $product->id]);

        $this->assertInstanceOf(StockHistory::class, $product->stockHistories->first());
        $this->assertCount(3, $product->stockHistories);
    }
}
