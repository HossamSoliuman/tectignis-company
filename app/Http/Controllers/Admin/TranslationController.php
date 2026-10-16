<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmTranslationsRequest;
use App\Http\Requests\Admin\ImportTranslationsRequest;
use App\Models\GlossaryTerm;
use App\Models\TranslationExport;
use App\Services\Translations\TranslationCatalog;
use App\Services\Translations\TranslationExporter;
use App\Services\Translations\TranslationImporter;
use App\Services\Translations\TranslationItem;
use App\Support\Locales;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * The Translations tab: check what is missing or outdated in each language,
 * export batches for an AI model, and import the results after review.
 */
class TranslationController extends Controller
{
    public function index(TranslationCatalog $catalog, TranslationExporter $exporter): View
    {
        return view('admin.translations.index', [
            'languages' => Locales::prefixed(),
            'summary' => $catalog->summary(),
            'batches' => $catalog->batches(),
            'prompt' => $exporter->prompt(),
            'glossary' => GlossaryTerm::query()->orderBy('locale')->orderBy('term')->get()->groupBy('locale'),
        ]);
    }

    /**
     * Download one batch of pending items as a JSON file.
     */
    public function export(Request $request, TranslationExporter $exporter): Response
    {
        $batch = (int) $request->validate(['batch' => ['required', 'integer', 'min:1']])['batch'];
        $file = $exporter->export($batch, $request->user());

        if ($file === null) {
            return redirect()->route('admin.translations.index')->with('status', 'That batch has nothing left to translate.');
        }

        return response()->streamDownload(
            fn () => print (json_encode($file, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)),
            'translations-'.$file['export_id'].'.json',
            ['Content-Type' => 'application/json; charset=UTF-8'],
        );
    }

    /**
     * Check an uploaded file and show it for review before anything is saved.
     */
    public function import(ImportTranslationsRequest $request, TranslationImporter $importer): View|RedirectResponse
    {
        try {
            ['export' => $export, 'values' => $values] = $importer->read((string) $request->file('file')->get());
        } catch (ValidationException $exception) {
            return redirect()->route('admin.translations.index')->withErrors($exception->errors());
        }

        return $this->preview($export, $importer->review($export, $values));
    }

    /**
     * Save the reviewed translations. Every value is checked again, so an
     * entry edited in the preview is saved only once it passes.
     */
    public function confirm(ConfirmTranslationsRequest $request, TranslationImporter $importer): View|RedirectResponse
    {
        $export = TranslationExport::where('export_id', $request->validated('export_id'))->firstOrFail();
        $entries = $importer->review($export, (array) $request->input('values', []));
        $problems = array_filter($entries, fn (array $entry): bool => $entry['error'] !== null);

        if ($request->boolean('review') && $problems !== []) {
            return $this->preview($export, $entries);
        }

        $saved = $importer->save($entries, $request->user());
        $skipped = count($entries) - $saved;

        return redirect()->route('admin.translations.index')->with(
            'status',
            "Saved {$saved} ".str('translation')->plural($saved).' from export '.$export->export_id.'.'
                .($skipped > 0 ? " Skipped {$skipped} with problems; they stay in the next check." : ''),
        );
    }

    /**
     * @param  list<array{id: string, locale: string, item: TranslationItem|null, value: string, error: string|null}>  $entries
     */
    private function preview(TranslationExport $export, array $entries): View
    {
        return view('admin.translations.preview', [
            'export' => $export,
            'items' => collect($entries)->groupBy('id'),
            'problemCount' => count(array_filter($entries, fn (array $entry): bool => $entry['error'] !== null)),
        ]);
    }
}
