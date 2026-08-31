<?php

namespace Database\Factories\Portal;

use App\Enums\Portal\EligibilityStatus;
use App\Enums\Portal\TenderDecision;
use App\Enums\Portal\TenderPortalSource;
use App\Enums\Portal\TenderStage;
use App\Models\Portal\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tender>
 */
class TenderFactory extends Factory
{
    protected $model = Tender::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'TND-'.Str::padLeft((string) fake()->unique()->numberBetween(1, 999999), 6, '0'),
            'tender_number' => Str::upper(fake()->bothify('GEM/2026/B/#######')),
            'title' => Str::headline(fake()->words(5, true)),
            'customer_organization' => fake()->company(),
            'portal' => TenderPortalSource::Gem,
            'tender_url' => null,
            'published_at' => now()->subDays(5),
            'pre_bid_at' => now()->addDays(2),
            'submission_start_at' => now()->subDay(),
            'submission_deadline_at' => now()->addDays(14),
            'estimated_value' => fake()->randomFloat(2, 100000, 20000000),
            'emd_required' => true,
            'emd_amount' => fake()->randomFloat(2, 10000, 200000),
            'fee_required' => false,
            'fee_amount' => null,
            'stage' => TenderStage::Identified,
            'decision' => TenderDecision::UnderReview,
            'eligibility_status' => EligibilityStatus::UnderReview,
            'completion_percent' => 0,
        ];
    }

    /**
     * Deadline inside the closing-soon horizon — the state the dashboard and
     * the countdown are built to catch.
     */
    public function closingSoon(): static
    {
        return $this->state(fn (array $attributes): array => [
            'submission_deadline_at' => now()->addDays(2),
            'stage' => TenderStage::DocumentationInProgress,
        ]);
    }

    public function deadlinePassed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'submission_deadline_at' => now()->subDays(2),
            'stage' => TenderStage::DocumentationInProgress,
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes): array => [
            'stage' => TenderStage::Submitted,
            'decision' => TenderDecision::Participate,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'stage' => TenderStage::Closed,
        ]);
    }
}
