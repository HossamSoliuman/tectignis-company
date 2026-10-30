<?php

namespace Database\Factories;

use App\Enums\CaseStudyStatus;
use App\Enums\ProjectStatus;
use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CaseStudy>
 */
class CaseStudyFactory extends Factory
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
            'status' => CaseStudyStatus::Published,
            'project_status' => ProjectStatus::Completed,
            'title' => fake()->words(4, true),
            'case_study_category_id' => null,
            'short_description' => fake()->sentence(),
            'image' => null,
            'content' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(1, 10),
            'is_featured' => false,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => CaseStudyStatus::Draft]);
    }

    public function archived(): static
    {
        return $this->state(['status' => CaseStudyStatus::Archived]);
    }

    public function ongoing(): static
    {
        return $this->state(['project_status' => ProjectStatus::Ongoing]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }
}
