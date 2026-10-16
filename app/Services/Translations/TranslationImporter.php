<?php

namespace App\Services\Translations;

use App\Models\Translation;
use App\Models\TranslationExport;
use App\Models\User;
use Illuminate\Support\Once;
use Illuminate\Validation\ValidationException;

/**
 * Reads a translated batch file, checks every translation in it, and saves
 * the ones that pass.
 */
class TranslationImporter
{
    public function __construct(private TranslationCatalog $catalog) {}

    /**
     * The export the file belongs to and its translations, `id` => `locale` => text.
     *
     * @return array{export: TranslationExport, values: array<string, array<string, string>>}
     *
     * @throws ValidationException when the file is not a batch exported here.
     */
    public function read(string $contents): array
    {
        $data = json_decode($contents, true);

        if (! is_array($data) || ! is_array($data['items'] ?? null)) {
            throw ValidationException::withMessages(['file' => 'The file is not valid JSON in the export format.']);
        }

        $export = TranslationExport::where('export_id', (string) ($data['export_id'] ?? ''))->first();

        if (! $export) {
            throw ValidationException::withMessages(['file' => 'The file does not come from an export made here. Check its export_id.']);
        }

        $values = [];

        foreach ($data['items'] as $item) {
            $id = is_array($item) ? ($item['id'] ?? null) : null;

            if (! is_string($id) || $export->sourceHashFor($id) === null) {
                throw ValidationException::withMessages(['file' => 'The file has an item that is not in export '.$export->export_id.': '.json_encode($id)]);
            }

            $values[$id] = array_filter((array) ($item['translations'] ?? []), is_string(...));
        }

        return ['export' => $export, 'values' => $values];
    }

    /**
     * Check each filled-in translation. An entry with an `error` is shown in
     * the preview and not saved; languages left empty are skipped and show up
     * again in the next check.
     *
     * @param  array<string, array<string, string|null>>  $values  `id` => `locale` => text
     * @return list<array{id: string, locale: string, item: TranslationItem|null, value: string, error: string|null}>
     */
    public function review(TranslationExport $export, array $values): array
    {
        $entries = [];

        foreach ($values as $id => $translations) {
            if ($export->sourceHashFor((string) $id) === null || ! is_array($translations)) {
                continue;
            }

            $item = $this->catalog->find((string) $id);

            foreach ($export->languagesFor((string) $id) as $locale) {
                $value = trim((string) ($translations[$locale] ?? ''));

                if ($value === '') {
                    continue;
                }

                $entries[] = [
                    'id' => (string) $id,
                    'locale' => $locale,
                    'item' => $item,
                    'value' => $value,
                    'error' => $this->problem($export, (string) $id, $item, $value),
                ];
            }
        }

        return $entries;
    }

    /**
     * Save every entry without an error, fingerprinted against the current
     * English, and refresh the site caches once.
     *
     * @param  list<array{id: string, locale: string, item: TranslationItem|null, value: string, error: string|null}>  $entries
     */
    public function save(array $entries, User $user): int
    {
        $valid = array_values(array_filter($entries, fn (array $entry): bool => $entry['error'] === null && $entry['item'] !== null));

        Translation::withoutEvents(function () use ($valid, $user): void {
            foreach ($valid as $entry) {
                $item = $entry['item'];

                Translation::updateOrCreate(
                    [
                        'translatable_type' => $item->record->getMorphClass(),
                        'translatable_id' => $item->record->getKey(),
                        'field' => $item->field,
                        'locale' => $entry['locale'],
                    ],
                    [
                        'value' => $entry['value'],
                        'source_hash' => $item->sourceHash(),
                        'imported_by' => $user->id,
                    ],
                );
            }
        });

        Translation::flushSiteCache();
        Once::flush();
        TranslationCatalog::forgetPendingCount();

        return count($valid);
    }

    private function problem(TranslationExport $export, string $id, ?TranslationItem $item, string $value): ?string
    {
        if ($item === null) {
            return 'This text is no longer on the site.';
        }

        if ($item->sourceHash() !== $export->sourceHashFor($id)) {
            return 'The English changed after the export, so this item will be in the next export.';
        }

        if ($this->tags($item->source) !== $this->tags($value)) {
            return 'The HTML tags differ from the English.';
        }

        if ($item->maxLength !== null && mb_strlen($value) > $item->maxLength) {
            return 'Longer than '.$item->maxLength.' characters ('.mb_strlen($value).').';
        }

        return null;
    }

    /**
     * The opening and closing tags in a text, sorted, e.g. ["/p", "/strong", "p", "strong"].
     *
     * @return list<string>
     */
    private function tags(string $html): array
    {
        preg_match_all('/<\s*(\/?)\s*([a-z][a-z0-9]*)/i', $html, $matches, PREG_SET_ORDER);

        $tags = array_map(fn (array $match): string => $match[1].strtolower($match[2]), $matches);
        sort($tags);

        return $tags;
    }
}
