<?php

namespace Database\Factories;

use App\Models\Industry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Industry>
 */
class IndustryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'slug' => (string) str($name)->slug(),
            'name' => ucfirst($name),
            'short_description' => fake()->sentence(),
            'description' => '<p>'.fake()->paragraph().'</p>',
            'body' => null,
            'icon' => 'fas fa-industry',
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
