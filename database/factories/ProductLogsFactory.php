<?php

namespace Database\Factories;

use App\Models\ProductLogs;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductLogsFactory extends Factory
{
    protected $model = ProductLogs::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'action' => $this->faker->randomElement(['created', 'updated', 'deleted']),
            'description' => $this->faker->sentence,
        ];
    }
}
