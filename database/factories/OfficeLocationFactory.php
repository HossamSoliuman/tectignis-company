<?php

namespace Database\Factories;

use App\Models\OfficeLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OfficeLocation>
 */
class OfficeLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'region' => fake()->randomElement(['india', 'global']),
            'city' => fake()->city(),
            'type' => fake()->randomElement(['Head Office', 'Regional Office', 'Development Center']),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
