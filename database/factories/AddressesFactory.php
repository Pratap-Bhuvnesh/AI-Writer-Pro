<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Addreses>
 */
class AddressesFactory extends Factory
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

            'address_line1' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'India',

            'is_default' => false, // handled in seeder

            'phone' => fake()->phoneNumber(),

            'type' => fake()->randomElement(['home', 'office']),

            'meta' => [
                'landmark' => fake()->word(),
                'alternate_phone' => fake()->phoneNumber(),
                'delivery_instructions' => fake()->sentence(),
            ],
        ];
    }
}
