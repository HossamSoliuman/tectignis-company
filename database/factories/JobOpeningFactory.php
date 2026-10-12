<?php

namespace Database\Factories;

use App\Enums\JobOpeningStatus;
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
            'experience' => '2–4 years',
            'skills' => "Laravel\nMySQL\nREST APIs",
            'description' => '<p>'.fake()->paragraph().'</p>',
            'responsibilities' => implode("\n", fake()->sentences(3)),
            'requirements' => implode("\n", fake()->sentences(3)),
            'status' => JobOpeningStatus::Published,
            'sort_order' => 0,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => JobOpeningStatus::Draft]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => JobOpeningStatus::Closed]);
    }
}
