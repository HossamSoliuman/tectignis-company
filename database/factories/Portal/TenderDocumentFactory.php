<?php

namespace Database\Factories\Portal;

use App\Enums\Portal\TenderDocumentCategory;
use App\Enums\Portal\TenderDocumentStatus;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TenderDocument>
 */
class TenderDocumentFactory extends Factory
{
    protected $model = TenderDocument::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'name' => Str::headline(fake()->words(3, true)),
            'category' => TenderDocumentCategory::Company,
            'is_required' => true,
            'status' => TenderDocumentStatus::Pending,
            'required_by' => now()->addDays(7),
            'version' => 1,
            'sort_order' => 0,
        ];
    }

    public function optional(): static
    {
        return $this->state(fn (array $attributes): array => ['is_required' => false]);
    }

    public function uploaded(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => TenderDocumentStatus::Uploaded]);
    }

    public function ready(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => TenderDocumentStatus::Ready]);
    }

    /**
     * On file but out of date — useless for a bid, and easy to miss.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TenderDocumentStatus::Uploaded,
            'expires_on' => now()->subMonth(),
        ]);
    }
}
