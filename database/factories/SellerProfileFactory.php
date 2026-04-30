<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SellerProfile;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SellerProfile>
 */
class SellerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SellerProfile::class;

    public function definition(): array
    {
        $statuses = ['pending', 'approved', 'rejected'];

        $status = $this->faker->randomElement($statuses);

        return [
            'user_id' => User::factory(), // ensures unique user
            'store_name' => $this->faker->company(),
            'business_type' => $this->faker->randomElement(['individual', 'company']),
            'contact_phone' => $this->faker->phoneNumber(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'address' => $this->faker->address(),
            'description' => $this->faker->paragraph(),
            'status' => $status,
            'approved_at' => $status === 'approved'
                ? $this->faker->dateTimeBetween('-1 year', 'now')
                : null,
        ];
    }
}
