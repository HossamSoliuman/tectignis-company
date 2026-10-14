<?php

namespace App\Models;

use App\Models\Concerns\HasContentScopes;
use Database\Factories\LandingPageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A focused Google Ads landing page for one priority service. The hero, FAQs
 * and form copy belong to the page; benefits, process and project examples
 * come from the linked service so they are maintained in one place.
 */
class LandingPage extends Model
{
    /** @use HasFactory<LandingPageFactory> */
    use HasContentScopes, HasFactory;

    protected $fillable = [
        'slug', 'title', 'service_id', 'enquiry_service', 'headline', 'supporting_text',
        'primary_cta_label', 'secondary_cta_label', 'content', 'seo_title', 'seo_description',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }

    /**
     * The service whose benefits, process and case studies the page shows.
     *
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Selling points listed under the hero copy.
     *
     * @return list<string>
     */
    public function bullets(): array
    {
        return array_values(array_filter((array) ($this->content['bullets'] ?? []), fn (mixed $bullet): bool => filled($bullet)));
    }

    /**
     * The page's own FAQs, or the linked service's when none are set.
     *
     * @return list<array{question: string, answer: string}>
     */
    public function faqItems(): array
    {
        $items = (array) ($this->content['faq']['items'] ?? []);

        if ($items === [] && ($this->service?->content['faq']['enabled'] ?? true)) {
            $items = (array) ($this->service?->content['faq']['items'] ?? []);
        }

        return array_values(array_filter($items, fn (mixed $item): bool => filled($item['question'] ?? null)));
    }

    /**
     * Benefits from the linked service.
     *
     * @return list<array{title: string, description?: string}>
     */
    public function benefits(): array
    {
        return $this->serviceItems('benefits', 'items');
    }

    /**
     * Delivery stages from the linked service.
     *
     * @return list<array{title: string, description?: string}>
     */
    public function processSteps(): array
    {
        return $this->serviceItems('process', 'steps');
    }

    /**
     * Project examples: the case studies picked for the linked service.
     *
     * @return Collection<int, CaseStudy>
     */
    public function caseStudies(int $limit = 3): Collection
    {
        if (! $this->service || ! ($this->service->content['case_studies']['enabled'] ?? true)) {
            return new Collection;
        }

        return $this->service->selectedCaseStudies()->take($limit)->values();
    }

    /**
     * A content value set on the page, e.g. `form.heading`.
     */
    public function contentValue(string $key, ?string $default = null): ?string
    {
        $value = data_get($this->content, $key);

        return filled($value) ? (string) $value : $default;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function serviceItems(string $section, string $listKey): array
    {
        $content = $this->service?->content[$section] ?? [];

        if (! ($content['enabled'] ?? true)) {
            return [];
        }

        return array_values(array_filter((array) ($content[$listKey] ?? []), fn (mixed $item): bool => filled($item['title'] ?? null)));
    }
}
