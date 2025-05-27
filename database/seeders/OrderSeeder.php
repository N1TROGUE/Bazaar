<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'seller_id' => 2,
            'buyer_id' => 1,
            'advertisement_id' => 1,
            'final_price' => 300.00,
        ]);

        Order::create([
            'seller_id' => 2,
            'buyer_id' => 1,
            'advertisement_id' => 2,
            'final_price' => 150.00,
        ]);

        Order::create([
            'seller_id' => 2,
            'buyer_id' => 1,
            'advertisement_id' => 3,
            'final_price' => 200.00,
        ]);
    }
}
