<?php

namespace Database\Factories;

use App\Models\WhyChooseFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WhyChooseFeature>
 */
class WhyChooseFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'icon' => 'fas fa-globe',
            'title' => fake()->unique()->words(2, true),
            'text' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
