<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPUnit\Framework\Attributes\Test;

class SupplierUnitTest extends TestCase
{
    #[Test]
    public function supplier_has_many_products_relation_is_defined()
    {
        $supplier = new Supplier();
        $this->assertTrue(method_exists($supplier, "products"));
        $this->assertInstanceOf(HasMany::class, $supplier->products());
    }
}
