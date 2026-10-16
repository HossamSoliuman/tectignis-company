<?php

namespace App\Services\Translations;

use App\Models\GlossaryTerm;
use App\Models\TranslationExport;
use App\Models\User;
use App\Support\Locales;
use Illuminate\Support\Collection;

/**
 * Builds the batch files the admin hands to an AI model, and the prompt that
 * goes with them.
 */
class TranslationExporter
{
    public function __construct(private TranslationCatalog $catalog) {}

    /**
     * Record batch N of the pending items as an export and return its file
     * contents, or null when there is no such batch.
     *
     * @return array{export_id: string, languages: list<string>, items: list<array{id: string, context: string, max_length: int|null, source: string, translations: array<string, string>}>}|null
     */
    public function export(int $batch, User $user): ?array
    {
        /** @var Collection<int, array{item: TranslationItem, languages: list<string>}>|null $rows */
        $rows = $this->catalog->batches()->get($batch - 1);

        if ($rows === null || $rows->isEmpty()) {
            return null;
        }

        $languages = array_values(array_intersect(Locales::prefixed(), $rows->pluck('languages')->flatten()->unique()->all()));

        $export = TranslationExport::create([
            'export_id' => TranslationExport::nextExportId(),
            'languages' => $languages,
            'items' => $rows->mapWithKeys(fn (array $row): array => [
                $row['item']->id => ['source_hash' => $row['item']->sourceHash(), 'languages' => $row['languages']],
            ])->all(),
            'created_by' => $user->id,
        ]);

        return [
            'export_id' => $export->export_id,
            'languages' => $languages,
            'items' => $rows->map(fn (array $row): array => [
                'id' => $row['item']->id,
                'context' => $row['item']->context,
                'max_length' => $row['item']->maxLength,
                'source' => $row['item']->source,
                'translations' => array_fill_keys($row['languages'], ''),
            ])->all(),
        ];
    }

    /**
     * The instructions to send with a batch file, including each language's
     * glossary.
     */
    public function prompt(): string
    {
        $glossary = GlossaryTerm::query()->orderBy('locale')->orderBy('term')->get()->groupBy('locale');

        return view('admin.translations.prompt', [
            'languages' => collect(Locales::prefixed())->mapWithKeys(fn (string $code): array => [$code => Locales::label($code)]),
            'glossary' => $glossary,
        ])->render();
    }
}
