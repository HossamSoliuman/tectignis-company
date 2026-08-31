<?php

namespace Database\Factories\Portal;

use App\Enums\Portal\ChecklistItemStatus;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderChecklistItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TenderChecklistItem>
 */
class TenderChecklistItemFactory extends Factory
{
    protected $model = TenderChecklistItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'requirement' => Str::headline(fake()->words(4, true)),
            'category' => 'general',
            'status' => ChecklistItemStatus::Pending,
            'remarks' => null,
            'sort_order' => 0,
        ];
    }

    public function met(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => ChecklistItemStatus::Met]);
    }

    /**
     * One unmet requirement is all it takes to make a tender Not Eligible.
     */
    public function notMet(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => ChecklistItemStatus::NotMet]);
    }

    public function notApplicable(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => ChecklistItemStatus::NotApplicable]);
    }
}
