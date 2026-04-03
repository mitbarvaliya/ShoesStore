<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        $markets = [
            ['name' => 'Apple Inc.', 'symbol' => 'AAPL', 'type' => 'stock', 'price' => 178.50, 'previous_price' => 177.25],
            ['name' => 'Tesla Inc.', 'symbol' => 'TSLA', 'type' => 'stock', 'price' => 245.00, 'previous_price' => 248.75],
            ['name' => 'Reliance Industries', 'symbol' => 'RELIANCE', 'type' => 'stock', 'price' => 2850.00, 'previous_price' => 2840.00],
            ['name' => 'Gold', 'symbol' => 'XAU', 'type' => 'gold', 'price' => 2300.50, 'previous_price' => 2295.00],
            ['name' => 'Silver', 'symbol' => 'XAG', 'type' => 'silver', 'price' => 27.50, 'previous_price' => 28.00],
            ['name' => 'Copper', 'symbol' => 'XCU', 'type' => 'copper', 'price' => 4.50, 'previous_price' => 4.45],
        ];

        foreach ($markets as $market) {
            Market::create($market);
        }
    }
}
