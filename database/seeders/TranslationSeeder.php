<?php

namespace Database\Seeders;

use App\Models\Translation;
use App\Services\Translations\TranslationCatalog;
use App\Services\Translations\TranslationImporter;
use Illuminate\Database\Seeder;

/**
 * Saves the reviewed translation round in database/seeders/translations/
 * for every item the Translations check lists as missing or outdated. An
 * entry is used only while its English still matches the site's, so text
 * edited since the round stays in the next check. Translations already
 * imported for the current English are never touched.
 */
class TranslationSeeder extends Seeder
{
    public function run(TranslationCatalog $catalog, TranslationImporter $importer): void
    {
        $memory = $this->memory();
        $entries = [];

        foreach ($catalog->pending() as ['item' => $item, 'languages' => $languages]) {
            foreach ($languages as $locale) {
                $value = $memory[$locale][$item->id.'|'.$item->sourceHash()] ?? $memory[$locale][$item->sourceHash()] ?? null;

                if ($value === null) {
                    continue;
                }

                $entries[] = [
                    'id' => $item->id,
                    'locale' => $locale,
                    'item' => $item,
                    'value' => $value,
                    'error' => $importer->check($item, $value),
                ];
            }
        }

        $saved = $importer->save($entries, null);

        $this->command?->info("Saved {$saved} translations.");

        foreach ($entries as $entry) {
            if ($entry['error'] !== null) {
                $this->command?->warn("Skipped {$entry['id']} ({$entry['locale']}): {$entry['error']}");
            }
        }
    }

    /**
     * The round's translations per language, keyed by item ID and English
     * fingerprint, and by fingerprint alone for records whose IDs differ.
     *
     * @return array<string, array<string, string>>
     */
    private function memory(): array
    {
        $memory = [];

        foreach (glob(__DIR__.'/translations/*.json') as $file) {
            $data = json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);

            foreach ($data['items'] as $item) {
                $hash = Translation::hash($item['source']);

                $memory[$data['locale']][$item['id'].'|'.$hash] = $item['translation'];
                $memory[$data['locale']][$hash] ??= $item['translation'];
            }
        }

        return $memory;
    }
}
