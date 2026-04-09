<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\UserRoleSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ProductVariantSeeder;
use Database\Seeders\ProductImageSeeder;
use Database\Seeders\InventorySeeder;
use Database\Seeders\CartSeeder;
use Database\Seeders\CartItemSeeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\OrderItemSeeder;
use Database\Seeders\AddresesSeeder;
use Database\Seeders\ShipmentSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\CouponSeeder;
use Database\Seeders\CouponUsageSeeder;
use Database\Seeders\PaymentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,            
            UserRoleSeeder::class,
            RolePermissionSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,            
            ProductVariantSeeder::class,
            ProductImageSeeder::class,
            InventorySeeder::class,
            CartSeeder::class,
            CartItemSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
            AddresesSeeder::class,
            ShipmentSeeder::class,
            ReviewSeeder::class,
            CouponSeeder::class,
            CouponUsageSeeder::class,            
        ]);         
    }
}
