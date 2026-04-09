<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::inRandomOrder()->value('id'),
            'sku' => strtoupper(fake()->bothify('SKU-####')),
            'price' => fake()->randomFloat(2, 100, 1000),
            'discount_price' => fake()->optional()->randomFloat(2, 50, 900),
            'attributes' => [
                'size' => fake()->randomElement(['S', 'M', 'L']),
                'color' => fake()->safeColorName(),
            ],
        ];
    }
}
