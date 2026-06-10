<?php

namespace Database\Factories;

use App\Models\Insight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Insight>
 */
class InsightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(4),
            'title' => fake()->sentence(6),
            'topic' => fake()->randomElement(['Cloud Computing', 'Cyber Security', 'AI & Automation', 'IT Infrastructure', 'Data & Analytics']),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'image' => null,
            'author' => 'Tectignis Team',
            'published_at' => now(),
            'is_published' => true,
        ];
    }
}
