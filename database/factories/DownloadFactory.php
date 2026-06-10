<?php

namespace Database\Factories;

use App\Models\Download;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Download>
 */
class DownloadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'category' => fake()->randomElement(array_keys(Download::CATEGORIES)),
            'file_type' => fake()->randomElement(Download::FILE_TYPES),
            'description' => fake()->sentence(),
            'file' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
