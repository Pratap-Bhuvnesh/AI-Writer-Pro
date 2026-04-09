<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'user_id' => User::inRandomOrder()->value('id'),

            'total_amount' => $this->faker->randomFloat(2, 100, 5000),

            'status' => $this->faker->randomElement([
                'pending',
                'shipped',
                'delivered',
                'cancelled'
            ]),

            'payment_status' => $this->faker->randomElement([
                'pending',
                'paid',
                'failed'
            ]),

            'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),

            'shipping_address' => $this->faker->address(),

            'payment_method' => $this->faker->randomElement([
                'cod',
                'card',
                'upi'
            ]),
        ];
    }
}
