<?php

namespace Database\Seeders;

use App\Models\StockHistory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StockHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockHistory::create([
            'product_id' => 1,
            'quantity' => 50,
            'date' => '2025-10-15',
            'type' => 'in',
        ]);

        StockHistory::create([
            'product_id' => 1,
            'quantity' => 10,
            'date' => '2025-10-17',
            'type' => 'out',
        ]);

        StockHistory::create([
            'product_id' => 2,
            'quantity' => 30,
            'date' => '2025-10-16',
            'type' => 'in',
        ]);

        StockHistory::create([
            'product_id' => 2,
            'quantity' => 5,
            'date' => '2025-10-19',
            'type' => 'out',
        ]);

        StockHistory::create([
            'product_id' => 3,
            'quantity' => 80,
            'date' => '2025-10-18',
            'type' => 'in',
        ]);

        StockHistory::create([
            'product_id' => 3,
            'quantity' => 20,
            'date' => '2025-10-20',
            'type' => 'out',
        ]);
    }
}
