<?php

use App\Models\Setting;
use App\Models\Stat;
use App\Models\Translation;
use App\Services\Translations\TranslationImporter;
use App\Services\Translations\TranslationItem;
use App\Support\Locales;
use Database\Seeders\TranslationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * The round's entries per language, keyed by item ID.
 *
 * @return array<string, array<string, array{id: string, context: string, max_length?: int, source: string, translation: string}>>
 */
function translationRound(): array
{
    $round = [];

    foreach (glob(database_path('seeders/translations/*.json')) as $file) {
        $data = json_decode(file_get_contents($file), true);
        $round[$data['locale']] = collect($data['items'])->keyBy('id')->all();
    }

    return $round;
}

function roundTranslation(string $locale, string $id): string
{
    return translationRound()[$locale][$id]['translation'];
}

it('saves the round for every language when the English matches', function () {
    $stat = Stat::factory()->create(['label' => 'Projects Delivered']);

    $this->seed(TranslationSeeder::class);

    foreach (Locales::prefixed() as $locale) {
        $translation = $stat->translations()->where('field', 'label')->where('locale', $locale)->sole();

        expect($translation->value)->toBe(roundTranslation($locale, 'stat.1.label'))
            ->and($translation->source_hash)->toBe(Translation::hash('Projects Delivered'))
            ->and($translation->imported_by)->toBeNull();
    }
});

it('matches records by their English when their IDs differ', function () {
    Stat::factory()->create(['label' => 'Happy Clients']);
    $projects = Stat::factory()->create(['label' => 'Projects Delivered']);

    $this->seed(TranslationSeeder::class);

    expect($projects->translations()->where('locale', 'es')->value('value'))->toBe(roundTranslation('es', 'stat.1.label'));
});

it('leaves text edited since the round for the next check', function () {
    $stat = Stat::factory()->create(['label' => 'Projects Shipped']);

    $this->seed(TranslationSeeder::class);

    expect($stat->translations()->count())->toBe(0);
});

it('keeps a translation imported for the current English', function () {
    $stat = Stat::factory()->create(['label' => 'Projects Delivered']);
    Translation::factory()->of($stat, 'label', 'Proyectos realizados', 'es')->create();

    $this->seed(TranslationSeeder::class);

    expect($stat->translations()->where('locale', 'es')->sole()->value)->toBe('Proyectos realizados')
        ->and($stat->translations()->where('locale', 'fr')->sole()->value)->toBe(roundTranslation('fr', 'stat.1.label'));
});

it('replaces an outdated translation', function () {
    $stat = Stat::factory()->create(['label' => 'Projects Delivered']);
    Translation::factory()->create([
        'translatable_type' => $stat->getMorphClass(),
        'translatable_id' => $stat->id,
        'field' => 'label',
        'locale' => 'es',
        'value' => 'Proyectos',
        'source_hash' => Translation::hash('Projects'),
    ]);

    $this->seed(TranslationSeeder::class);

    $translation = $stat->translations()->where('locale', 'es')->sole();

    expect($translation->value)->toBe(roundTranslation('es', 'stat.1.label'))
        ->and($translation->source_hash)->toBe(Translation::hash('Projects Delivered'));
});

it('translates the same English in every language and passes the import checks', function () {
    $round = translationRound();
    $importer = app(TranslationImporter::class);
    $english = array_map(fn (array $item): string => $item['source'], reset($round));

    expect(array_keys($round))->toEqualCanonicalizing(Locales::prefixed());

    foreach ($round as $locale => $items) {
        expect(array_map(fn (array $item): string => $item['source'], $items))->toBe($english);

        foreach ($items as $id => $item) {
            $source = new TranslationItem($id, '', $item['context'], $item['source'], $item['max_length'] ?? null, new Setting, 'value');
            $problem = $importer->check($source, $item['translation']);

            expect($problem)->toBeNull("{$locale} {$id}: {$problem}");
        }
    }
});

it('follows the prompt rules for punctuation and digits', function () {
    $round = translationRound();

    foreach ($round['fr'] as $id => $item) {
        expect($item['translation'])->not->toMatch('/ [:;!?]/u', "fr {$id}: ordinary space before punctuation");
    }

    foreach (['ar' => '/[\x{0660}-\x{0669}\x{06F0}-\x{06F9}]/u', 'hi' => '/[\x{0966}-\x{096F}]/u'] as $locale => $nativeDigits) {
        foreach ($round[$locale] as $id => $item) {
            expect($item['translation'])->not->toMatch($nativeDigits, "{$locale} {$id}: native digits");
        }
    }
});
