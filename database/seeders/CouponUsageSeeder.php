<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\CouponUsage;

class CouponUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::where('payment_status', 'paid')->get();
        $coupons = Coupon::all();

        foreach ($orders as $order) {

            // randomly decide if coupon used
            if (rand(0, 1)) {

                $coupon = $coupons->random();

                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                ]);

                // update usage count
                $coupon->increment('used_count');
            }
        }
    }
}
