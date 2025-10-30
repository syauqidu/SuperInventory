<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductLog;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductLogs;
use Illuminate\Support\Facades\DB;

class ProductLogSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->count(3)->create();
        }

        if (Product::count() === 0) {
            Product::factory()->count(5)->create();
        }

        $users = User::all();
        $products = Product::all();

        $actions = ['created', 'updated', 'deleted'];

        foreach (range(1, 15) as $i) {
            $user = $users->random();
            $product = $products->random();
            $action = $actions[array_rand($actions)];

            ProductLogs::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'action' => $action,
                'description' => ucfirst($action) . ' product ' . $product->name . ' by ' . $user->name,
                'created_at' => now()->subDays(rand(0, 30))->setTime(rand(8, 18), rand(0, 59)),
            ]);
        }
    }
}
