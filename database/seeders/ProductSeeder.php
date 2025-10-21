<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'supplier_id' => 1,
                'name' => 'Sabun Lifebuoy 100ml',
                'category' => 'Kebutuhan Rumah Tangga',
                'stock' => 50,
                'unit' => 'pcs',
            ],
            [
                'supplier_id' => 1,
                'name' => 'Shampoo Clear 170ml',
                'category' => 'Kebutuhan Rumah Tangga',
                'stock' => 40,
                'unit' => 'pcs',
            ],
            [
                'supplier_id' => 2,
                'name' => 'Indomie Goreng',
                'category' => 'Makanan',
                'stock' => 200,
                'unit' => 'pcs',
            ],
            [
                'supplier_id' => 2,
                'name' => 'Minyak Goreng Bimoli 1L',
                'category' => 'Makanan',
                'stock' => 120,
                'unit' => 'liter',
            ],
            [
                'supplier_id' => 3,
                'name' => 'Beras Pandan Wangi 5kg',
                'category' => 'Sembako',
                'stock' => 80,
                'unit' => 'karung',
            ],
            [
                'supplier_id' => 3,
                'name' => 'Gula Pasir 1kg',
                'category' => 'Sembako',
                'stock' => 150,
                'unit' => 'pcs',
            ],
        ]);
    }
}
