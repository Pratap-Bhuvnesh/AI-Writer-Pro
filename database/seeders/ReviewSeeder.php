<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
    $products = Product::all();

    foreach ($products as $product) {

        // pick random users (no duplicates)
        $randomUsers = $users->random(min(5, $users->count()));

        foreach ($randomUsers as $user) {

            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => rand(1, 5),
                'comment' => fake()->sentence(),
            ]);
        }
    }
    }
}
