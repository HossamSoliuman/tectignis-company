<?php

namespace Database\Factories;

use App\Models\Solution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Solution>
 */
class SolutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'slug' => (string) str($title)->slug(),
            'title' => $title,
            'short_description' => fake()->sentence(),
            'description' => '<p>'.fake()->paragraph().'</p>',
            'body' => null,
            'icon' => 'fas fa-cogs',
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
            'seo_title' => fake()->sentence(),
            'seo_description' => fake()->sentence(),
        ];
    }
}
