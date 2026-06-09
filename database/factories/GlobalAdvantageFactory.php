<?php

namespace Database\Factories;

use App\Models\GlobalAdvantage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GlobalAdvantage>
 */
class GlobalAdvantageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'icon' => '<path d="M12 6v6h4.5"/>',
            'title' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'tone' => fake()->randomElement(['rose', 'indigo']),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
