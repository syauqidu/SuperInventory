<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 50),
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['in', 'out']),
        ];
    }
}
