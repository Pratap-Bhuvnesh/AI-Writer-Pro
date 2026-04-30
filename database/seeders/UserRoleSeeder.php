<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /*  $user = \App\Models\User::first();

        $user->roles()->attach([
            1, // admin
            2  // customer
        ]); */
       $sellerIds = \App\Models\SellerProfile::pluck('id')->toArray();

\App\Models\Product::all()->each(function ($product) use ($sellerIds) {
    $product->update([
        'seller_id' => fake()->randomElement($sellerIds)
    ]);
});
    }
}
