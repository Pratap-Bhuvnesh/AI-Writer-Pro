<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\CartItem;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = Cart::all();

    foreach ($carts as $cart) {

        // pick random variants (no duplicates per cart)
        $variants = ProductVariant::inRandomOrder()->take(3)->get();

        foreach ($variants as $variant) {

            $inventory = $variant->inventory;

            if (!$inventory || $inventory->stock_quantity <= 0) {
                continue;
            }

            $qty = rand(1, min(3, $inventory->stock_quantity));

            DB::transaction(function () use ($cart, $variant, $inventory, $qty) {

                // reserve stock
                $inventory->increment('reserved_quantity', $qty);

                // create cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $qty,
                ]);
            });
        }
    }
    }
}
