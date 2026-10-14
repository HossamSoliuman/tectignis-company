<?php

namespace Database\Factories;

use App\Models\LandingPage;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LandingPage>
 */
class LandingPageFactory extends Factory
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
            'title' => fake()->words(3, true),
            'service_id' => Service::factory(),
            'enquiry_service' => 'Custom Software',
            'headline' => 'Custom Software Development for Growing Businesses',
            'supporting_text' => fake()->sentence(12),
            'primary_cta_label' => 'Discuss Your Project',
            'secondary_cta_label' => 'Request a Consultation',
            'content' => [],
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    /**
     * A page switched off in the admin.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
