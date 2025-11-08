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

        $user = User::first();
        $product = Product::first();

        ProductLogs::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => 'created',
            'description' => 'Example: Created product ' . $product->name . ' by ' . $user->name,
            'created_at' => now()->subDays(3),
        ]);

        ProductLogs::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => 'updated',
            'description' => 'Example: Updated product ' . $product->name . ' (changed stock) by ' . $user->name,
            'created_at' => now()->subDays(2),
        ]);

        ProductLogs::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'action' => 'deleted',
            'description' => 'Example: Deleted product ' . $product->name . ' by ' . $user->name,
            'created_at' => now()->subDay(),
        ]);
    }
}
