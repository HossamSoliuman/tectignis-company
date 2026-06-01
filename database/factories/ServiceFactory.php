<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(3),
            'category' => fake()->randomElement(['it_services', 'digital_services']),
            'title' => fake()->words(3, true),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'icon' => 'website-development-com.webp',
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
            'seo_title' => fake()->sentence(),
            'seo_description' => fake()->sentence(),
        ];
    }
}
