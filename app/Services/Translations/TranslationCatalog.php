<?php

namespace App\Services\Translations;

use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use App\Models\GlobalAdvantage;
use App\Models\Industry;
use App\Models\LandingPage;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Models\Translation;
use App\Models\WhyChooseFeature;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Every English text on the translated pages that lives in the database, and
 * whether each language has a current translation of it. Fixed text in the
 * code is translated in the lang/ files instead.
 */
class TranslationCatalog
{
    public const AREA_HOMEPAGE = 'Homepage';

    public const AREA_SERVICES = 'Service pages';

    public const AREA_LANDING_PAGES = 'Landing pages';

    public const AREA_LEGAL = 'Legal';

    public const AREA_MENUS = 'Menus and cards';

    public const AREAS = [self::AREA_HOMEPAGE, self::AREA_SERVICES, self::AREA_LANDING_PAGES, self::AREA_LEGAL, self::AREA_MENUS];

    public const BATCH_SIZE = 30;

    private const PENDING_COUNT_CACHE_KEY = 'translations.pending';

    /**
     * Character limits for search result text.
     *
     * @var array<string, int>
     */
    private const MAX_LENGTHS = [
        'seo_title' => 60,
        'seo_description' => 160,
        'home_meta_title' => 60,
        'home_og_title' => 60,
        'home_meta_description' => 160,
        'home_og_description' => 160,
    ];

    /**
     * @var Collection<string, TranslationItem>|null
     */
    private ?Collection $items = null;

    /**
     * @var array<string, string>|null translation key => source hash
     */
    private ?array $translatedHashes = null;

    /**
     * @return Collection<string, TranslationItem> keyed by item ID
     */
    public function items(): Collection
    {
        return $this->items ??= collect([
            ...$this->homepageItems(),
            ...$this->serviceItems(),
            ...$this->landingPageItems(),
            ...$this->settingItems(),
        ])->keyBy('id');
    }

    public function find(string $id): ?TranslationItem
    {
        return $this->items()->get($id);
    }

    /**
     * `missing`, `outdated` (the English changed after it was translated) or `current`.
     */
    public function status(TranslationItem $item, string $locale): string
    {
        $hash = $this->translatedHashes()[$item->key($locale)] ?? null;

        return match (true) {
            $hash === null => 'missing',
            $hash !== $item->sourceHash() => 'outdated',
            default => 'current',
        };
    }

    /**
     * Items that need a translation in at least one language, each with the
     * languages that need it. Covers every supported language, switched on or not.
     *
     * @return Collection<int, array{item: TranslationItem, languages: list<string>}>
     */
    public function pending(): Collection
    {
        return $this->items()
            ->map(fn (TranslationItem $item): array => [
                'item' => $item,
                'languages' => array_values(array_filter(
                    Locales::prefixed(),
                    fn (string $locale): bool => $this->status($item, $locale) !== 'current',
                )),
            ])
            ->filter(fn (array $row): bool => $row['languages'] !== [])
            ->values();
    }

    /**
     * Pending items split into export batches.
     *
     * @return Collection<int, Collection<int, array{item: TranslationItem, languages: list<string>}>>
     */
    public function batches(): Collection
    {
        return $this->pending()->chunk(self::BATCH_SIZE)->map(fn (Collection $batch): Collection => $batch->values())->values();
    }

    /**
     * Missing and outdated counts per area and language.
     *
     * @return array<string, array<string, array{missing: int, outdated: int}>>
     */
    public function summary(): array
    {
        $summary = [];

        foreach (self::AREAS as $area) {
            foreach (Locales::prefixed() as $locale) {
                $summary[$area][$locale] = ['missing' => 0, 'outdated' => 0];
            }
        }

        foreach ($this->items() as $item) {
            foreach (Locales::prefixed() as $locale) {
                $status = $this->status($item, $locale);

                if ($status !== 'current') {
                    $summary[$item->area][$locale][$status]++;
                }
            }
        }

        return $summary;
    }

    /**
     * The number of items needing a translation, for the admin sidebar badge.
     * Cached briefly and refreshed after every import.
     */
    public static function pendingCount(): int
    {
        return Cache::remember(self::PENDING_COUNT_CACHE_KEY, now()->addMinutes(10), fn (): int => (new self)->pending()->count());
    }

    public static function forgetPendingCount(): void
    {
        Cache::forget(self::PENDING_COUNT_CACHE_KEY);
    }

    /**
     * @return list<TranslationItem>
     */
    private function homepageItems(): array
    {
        $sources = [
            [Stat::class, 'Stat'],
            [WhyChooseFeature::class, 'Why choose us point'],
            [ProcessStep::class, 'Process step'],
            [Industry::class, 'Industry'],
            [Testimonial::class, 'Testimonial'],
            [CaseStudyCategory::class, 'Case study category'],
            [CaseStudy::class, 'Case study'],
            [GlobalAdvantage::class, 'Global advantage'],
        ];

        $items = [];

        foreach ($sources as [$model, $label]) {
            $records = $model::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

            foreach ($records as $record) {
                $items = [...$items, ...$this->recordItems($record, self::AREA_HOMEPAGE, $label.' #'.$record->getKey())];
            }
        }

        return $items;
    }

    /**
     * The translated services in full; every other service only by the name
     * and short description that menus and cards show.
     *
     * @return list<TranslationItem>
     */
    private function serviceItems(): array
    {
        $items = [];

        foreach (Service::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get() as $service) {
            $items = Locales::isTranslatedPage('services.show', $service->slug)
                ? [...$items, ...$this->recordItems($service, self::AREA_SERVICES, $service->title.' page')]
                : [...$items, ...$this->recordItems($service, self::AREA_MENUS, 'Service "'.$service->title.'"', ['title', 'short_description'])];
        }

        return $items;
    }

    /**
     * @return list<TranslationItem>
     */
    private function landingPageItems(): array
    {
        $items = [];

        foreach (LandingPage::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get() as $landingPage) {
            $items = [...$items, ...$this->recordItems($landingPage, self::AREA_LANDING_PAGES, $landingPage->title.' landing page')];
        }

        return $items;
    }

    /**
     * @return list<TranslationItem>
     */
    private function settingItems(): array
    {
        $settings = Setting::query()->whereIn('key', Setting::TRANSLATABLE_KEYS)->get()->keyBy('key');
        $items = [];

        foreach (Setting::TRANSLATABLE_KEYS as $key) {
            $setting = $settings->get($key);

            if (! $setting || blank($setting->value)) {
                continue;
            }

            $isLegal = str_starts_with($key, 'legal_');

            $items[] = new TranslationItem(
                id: 'setting.'.$key,
                area: $isLegal ? self::AREA_LEGAL : self::AREA_HOMEPAGE,
                context: match (true) {
                    $isLegal => __('common.legal.'.substr($key, 6)).' page, full HTML',
                    $key === 'business_hours' => 'Footer and contact page, business hours',
                    default => 'Homepage, '.str_replace('_', ' ', $key),
                },
                source: (string) $setting->value,
                maxLength: self::MAX_LENGTHS[$key] ?? null,
                record: $setting,
                field: 'value',
            );
        }

        return $items;
    }

    /**
     * @param  list<string>|null  $onlyFields
     * @return list<TranslationItem>
     */
    private function recordItems(Model $record, string $area, string $label, ?array $onlyFields = null): array
    {
        $items = [];

        foreach ($record->translationSources() as $field => $source) {
            if ($onlyFields !== null && ! in_array($field, $onlyFields, true)) {
                continue;
            }

            $items[] = new TranslationItem(
                id: $record->getMorphClass().'.'.$record->getKey().'.'.$field,
                area: $area,
                context: $label.', '.$this->describeField($field),
                source: $source,
                maxLength: self::MAX_LENGTHS[$field] ?? null,
                record: $record,
                field: $field,
            );
        }

        return $items;
    }

    /**
     * `seo_title` → "meta title", `content.faq.items.2.answer` → "faq items #3 answer".
     */
    private function describeField(string $field): string
    {
        $named = ['seo_title' => 'meta title', 'seo_description' => 'meta description'];

        if (isset($named[$field])) {
            return $named[$field];
        }

        $parts = array_map(
            fn (string $part): string => ctype_digit($part) ? '#'.((int) $part + 1) : str_replace('_', ' ', $part),
            explode('.', $field),
        );

        if ($parts[0] === 'content' && count($parts) > 1) {
            array_shift($parts);
        }

        return implode(' ', $parts);
    }

    /**
     * @return array<string, string>
     */
    private function translatedHashes(): array
    {
        return $this->translatedHashes ??= Translation::query()
            ->get(['translatable_type', 'translatable_id', 'field', 'locale', 'source_hash'])
            ->mapWithKeys(fn (Translation $translation): array => [
                $translation->translatable_type.'|'.$translation->translatable_id.'|'.$translation->field.'|'.$translation->locale => $translation->source_hash,
            ])
            ->all();
    }
}
