<?php

namespace Database\Factories;

use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOpening>
 */
class JobOpeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'department' => fake()->randomElement(['Development', 'AI & Automation', 'Design', 'DevOps', 'Human Resources']),
            'icon' => 'fas fa-briefcase',
            'location' => 'Navi Mumbai, India',
            'employment_type' => 'Full-time',
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
