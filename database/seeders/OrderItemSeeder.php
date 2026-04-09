<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();

    foreach ($orders as $order) {

        // pick random unique variants
        $variants = ProductVariant::inRandomOrder()->take(3)->get();

        $total = 0;

        DB::transaction(function () use ($order, $variants, &$total) {

            foreach ($variants as $variant) {

                $price = $variant->discount_price ?? $variant->price;

                $qty = rand(1, 3);

                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $variant->id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);

                $total += $price * $qty;
            }

            // update order total
            $order->update([
                'total_amount' => $total
            ]);
        });
    }
    }
}
