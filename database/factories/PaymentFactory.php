<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'paid', 'failed']);

        return [
            'order_id' => Order::inRandomOrder()->value('id'),

            'payment_method' => $this->faker->randomElement([
                'upi',
                'card',
                'cod'
            ]),

            'payment_status' => $status,

            'transaction_id' => $status === 'paid'
                ? 'TXN-' . strtoupper(Str::random(10))
                : null,

            'paid_at' => $status === 'paid'
                ? now()
                : null,
        ];
    }
}
