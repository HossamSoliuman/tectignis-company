<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeadActivity>
 */
class LeadActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'user_id' => null,
            'type' => LeadActivity::CREATED,
        ];
    }
}
