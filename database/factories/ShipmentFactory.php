<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shippedAt = fake()->optional()->dateTimeBetween('-10 days', 'now');

        $deliveredAt = $shippedAt
            ? fake()->optional()->dateTimeBetween($shippedAt, 'now')
            : null;

        return [
            'order_id' => Order::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),

            'courier_name' => fake()->randomElement([
                'Delhivery',
                'BlueDart',
                'DTDC',
                'Ekart'
            ]),

            'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),

            'shipped_at' => $shippedAt,
            'delivered_at' => $deliveredAt,
        ];
    }
}
