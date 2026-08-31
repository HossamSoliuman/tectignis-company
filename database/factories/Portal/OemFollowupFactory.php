<?php

namespace Database\Factories\Portal;

use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\OemRequirementType;
use App\Models\Portal\OemFollowup;
use App\Models\Portal\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OemFollowup>
 */
class OemFollowupFactory extends Factory
{
    protected $model = OemFollowup::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'oem_name' => fake()->company(),
            'requirement_type' => OemRequirementType::Maf,
            'product' => fake()->words(2, true),
            'requested_on' => now()->subDays(3),
            'required_by' => now()->addDays(5),
            'contact_person' => fake()->name(),
            'contact_channel' => fake()->safeEmail(),
            'status' => OemFollowupStatus::Requested,
            'next_followup_at' => now()->addDay(),
        ];
    }

    /**
     * Chased before any tender needed it — the standalone case the policy
     * treats differently.
     */
    public function standalone(): static
    {
        return $this->state(fn (array $attributes): array => ['tender_id' => null]);
    }

    /**
     * The chase date has gone by and nobody has called — what the OEM queue
     * exists to surface.
     */
    public function due(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => OemFollowupStatus::Requested,
            'next_followup_at' => now()->subDay(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => OemFollowupStatus::InProcess,
            'required_by' => now()->subDays(2),
        ]);
    }

    public function received(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => OemFollowupStatus::Received,
            'next_followup_at' => null,
        ]);
    }
}
