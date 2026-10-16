<?php

namespace App\Services\Translations;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;

/**
 * One English text on the translated pages, as the Translations tab lists,
 * exports and imports it.
 */
final readonly class TranslationItem
{
    /**
     * @param  string  $id  Stable item ID in export files, e.g. `service.5.content.hero.heading` or `setting.core_heading`.
     * @param  string  $field  The translated field on the record: a column, a `content.*` path, or `value` for a setting.
     */
    public function __construct(
        public string $id,
        public string $area,
        public string $context,
        public string $source,
        public ?int $maxLength,
        public Model $record,
        public string $field,
    ) {}

    public function sourceHash(): string
    {
        return Translation::hash($this->source);
    }

    public function hasHtml(): bool
    {
        return $this->source !== strip_tags($this->source);
    }

    /**
     * The lookup key of this item's translation in one language.
     */
    public function key(string $locale): string
    {
        return $this->record->getMorphClass().'|'.$this->record->getKey().'|'.$this->field.'|'.$locale;
    }
}
