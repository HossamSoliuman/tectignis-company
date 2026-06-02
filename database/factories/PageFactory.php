<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'slug' => (string) str($title)->slug(),
            'title' => $title,
            'body' => '<div class="container"><p>'.fake()->paragraph().'</p></div>',
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
            'seo_title' => fake()->sentence(),
            'seo_description' => fake()->sentence(),
        ];
    }
}
