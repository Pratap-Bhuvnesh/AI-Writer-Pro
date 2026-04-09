<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'user_id' => null, // controlled in seeder
            'product_id' => null, // controlled

            'rating' => fake()->numberBetween(1, 5),

            'comment' => fake()->optional()->sentence(),
        ];
    }
}
