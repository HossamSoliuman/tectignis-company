<?php

namespace App\Models\Concerns;

use App\Models\Translation;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Serves a model's text in the current language. The English text stays in the
 * model's own columns, so the admin forms keep editing English; translations
 * live in the `translations` table and are laid over it when the page is in
 * another language. A field without a translation stays English.
 *
 * Fields are listed in the model's `$translatable`. For an array field such as
 * `content`, every text leaf is translated by its path (`content.hero.heading`)
 * while the structure, flags, IDs and icons always come from English.
 *
 * @property list<string> $translatable
 */
trait HasTranslations
{
    /**
     * Keys inside nested content whose strings are not text to translate.
     *
     * @var list<string>
     */
    public const NON_TEXT_CONTENT_KEYS = ['icon', 'image', 'url', 'slug'];

    /**
     * Translations already read for this record, per language.
     *
     * @var array<string, array<string, string>>
     */
    protected array $translationsByLocale = [];

    public static function bootHasTranslations(): void
    {
        // Load the current language's translations with the records, so a list
        // of records costs one extra query rather than one per record.
        static::addGlobalScope('translations', function (Builder $query): void {
            if (! Locales::isDefault()) {
                $query->with(['translations' => fn ($translations) => $translations->where('locale', Locales::current())]);
            }
        });

        static::deleted(function (self $model): void {
            $model->translations()->delete();
        });
    }

    /**
     * @return MorphMany<Translation, $this>
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * @return list<string>
     */
    public function translatableFields(): array
    {
        return $this->translatable;
    }

    public function getAttributeValue($key): mixed
    {
        $value = parent::getAttributeValue($key);

        if (Locales::isDefault() || ! in_array($key, $this->translatable, true)) {
            return $value;
        }

        $translations = $this->translationsFor(Locales::current());

        if (! is_array($value)) {
            return $translations[$key] ?? $value;
        }

        foreach ($translations as $field => $text) {
            $path = str_starts_with($field, $key.'.') ? substr($field, strlen($key) + 1) : null;

            if ($path !== null && is_string(data_get($value, $path))) {
                data_set($value, $path, $text);
            }
        }

        return $value;
    }

    /**
     * The English text of a field or `content.*` path, which translations are
     * made from and fingerprinted against.
     */
    public function translationSource(string $field): ?string
    {
        [$column, $path] = array_pad(explode('.', $field, 2), 2, null);
        $value = parent::getAttributeValue($column);

        if ($path !== null) {
            $value = is_array($value) ? data_get($value, $path) : null;
        }

        return is_scalar($value) ? (string) $value : null;
    }

    /**
     * Every translatable text of the record: `field` => English text. Array
     * fields are expanded to their text leaves.
     *
     * @return array<string, string>
     */
    public function translationSources(): array
    {
        $sources = [];

        foreach ($this->translatable as $column) {
            $value = parent::getAttributeValue($column);

            if (is_array($value)) {
                foreach (Arr::dot($value) as $path => $leaf) {
                    if (is_string($leaf) && filled($leaf) && ! $this->isNonTextPath((string) $path)) {
                        $sources[$column.'.'.$path] = $leaf;
                    }
                }
            } elseif (is_string($value) && filled($value)) {
                $sources[$column] = $value;
            }
        }

        return $sources;
    }

    /**
     * @return array<string, string> field => translated text
     */
    protected function translationsFor(string $locale): array
    {
        if (! array_key_exists($locale, $this->translationsByLocale)) {
            $rows = $this->relationLoaded('translations')
                ? $this->translations
                : $this->translations()->where('locale', $locale)->get();

            $this->translationsByLocale[$locale] = $rows instanceof Collection
                ? $rows->where('locale', $locale)->pluck('value', 'field')->all()
                : [];
        }

        return $this->translationsByLocale[$locale];
    }

    private function isNonTextPath(string $path): bool
    {
        $segments = explode('.', $path);

        return array_intersect($segments, self::NON_TEXT_CONTENT_KEYS) !== []
            || in_array('ids', $segments, true)
            || in_array('service_ids', $segments, true);
    }
}
