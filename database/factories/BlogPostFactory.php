<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
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
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'image' => null,
            'published_at' => now(),
            'is_published' => true,
            'seo_title' => fake()->sentence(),
            'seo_description' => fake()->sentence(),
        ];
    }
}
