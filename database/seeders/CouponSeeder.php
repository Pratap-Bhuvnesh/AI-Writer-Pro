<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::factory(10)->create();

    // Optional: create specific coupons
    Coupon::create([
        'code' => 'WELCOME10',
        'discount_type' => 'percentage',
        'discount_value' => 10,
        'expiry_date' => now()->addDays(30),
        'usage_limit' => 100,
    ]);

    Coupon::create([
        'code' => 'FLAT100',
        'discount_type' => 'fixed',
        'discount_value' => 100,
        'expiry_date' => now()->addDays(15),
    ]);
    }
}
