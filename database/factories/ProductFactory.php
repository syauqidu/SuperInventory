<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Supplier;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'supplier_id' => Supplier::factory(),
            'name' => $this->faker->word,
            'category' => $this->faker->word,
            'stock' => $this->faker->numberBetween(0, 100),
            'unit' => 'pcs',
        ];
    }
}
