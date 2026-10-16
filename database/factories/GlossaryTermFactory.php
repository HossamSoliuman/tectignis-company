<?php

namespace Database\Factories;

use App\Models\GlossaryTerm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GlossaryTerm>
 */
class GlossaryTermFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale' => 'ar',
            'term' => fake()->unique()->word(),
            'translation' => fake()->word(),
        ];
    }
}
