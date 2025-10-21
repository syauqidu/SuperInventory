<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'name' => 'ABC Supplies',
            'address' => '123 Main St, Cityville',
            'contact' => '555-1234',
        ]);

        Supplier::create([
            'name' => 'Global Traders',
            'address' => '456 Market Ave, Townsville',
            'contact' => '555-5678',
        ]);

        Supplier::create([
            'name' => 'Quality Goods Co.',
            'address' => '789 Commerce Rd, Villagetown',
            'contact' => '555-9012',
        ]);
    }
}
