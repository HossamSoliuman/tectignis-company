<?php

namespace Database\Factories;

use App\Models\CaseStudyCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CaseStudyCategory>
 */
class CaseStudyCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
