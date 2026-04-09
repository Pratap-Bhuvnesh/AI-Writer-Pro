<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\AdminLog;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminLog>
 */
class AdminLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     protected $model = AdminLog::class;

    public function definition(): array
    {
        $entities = ['product', 'order', 'user'];
        $actions = ['created', 'updated', 'deleted'];

        return [
            'admin_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'action' => $this->faker->randomElement($actions),
            'entity_type' => $this->faker->randomElement($entities),
            'entity_id' => \App\Models\Product::inRandomOrder()->value('id') ?? 1,
            'created_at' => now(),
        ];
    }
}
