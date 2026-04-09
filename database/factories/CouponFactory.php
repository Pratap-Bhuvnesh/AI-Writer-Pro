<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed']);

        return [
            'code' => strtoupper(Str::random(8)),

            'discount_type' => $type,

            'discount_value' => $type === 'percentage'
                ? fake()->numberBetween(5, 50) // %
                : fake()->numberBetween(50, 500), // ₹

            'expiry_date' => fake()->optional()->dateTimeBetween('now', '+30 days'),

            'usage_limit' => fake()->optional()->numberBetween(10, 100),

            'used_count' => 0,

            'is_active' => true,
        ];
    }
}
