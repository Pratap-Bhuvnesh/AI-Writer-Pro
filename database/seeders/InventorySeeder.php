<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Inventory;
class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //\App\Models\Inventory::factory(50)->create();
        ProductVariant::all()->each(function ($variant) {
        Inventory::factory()->create([
            'product_variant_id' => $variant->id,
        ]);
    });
    }
}
