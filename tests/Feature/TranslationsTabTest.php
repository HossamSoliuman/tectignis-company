<?php

use App\Enums\UserRole;
use App\Models\GlossaryTerm;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Translation;
use App\Models\TranslationExport;
use App\Models\User;
use App\Services\Translations\TranslationCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->editor = User::factory()->role(UserRole::Editor)->create();

    $this->service = Service::factory()->create([
        'is_active' => true,
        'slug' => 'custom-software-development',
        'title' => 'Custom Software Development',
        'short_description' => 'Software built around your business.',
        'description' => null,
        'body' => null,
        'seo_title' => 'Custom Software Development Company',
        'seo_description' => null,
        'content' => ['hero' => ['heading' => 'Software Built Around You', 'intro' => '<p>We build <strong>software</strong>.</p>']],
    ]);
});

/**
 * Download batch 1 and return the decoded file.
 *
 * @return array<string, mixed>
 */
function exportBatch(): array
{
    $response = test()->actingAs(test()->editor)->post(route('admin.translations.export'), ['batch' => 1]);
    $response->assertOk()->assertHeader('Content-Type', 'application/json; charset=UTF-8');

    return json_decode($response->streamedContent(), true);
}

/**
 * @param  array<string, mixed>  $file
 */
function uploadTranslations(array $file): UploadedFile
{
    return UploadedFile::fake()->createWithContent('translations.json', json_encode($file, JSON_UNESCAPED_UNICODE));
}

/**
 * @param  array<string, mixed>  $file
 * @param  array<string, array<string, string>>  $translations  id => locale => text
 * @return array<string, mixed>
 */
function fillTranslations(array $file, array $translations): array
{
    foreach ($file['items'] as $index => $item) {
        foreach ($translations[$item['id']] ?? [] as $locale => $text) {
            $file['items'][$index]['translations'][$locale] = $text;
        }
    }

    return $file;
}

it('is open to CMS users only', function (UserRole $role, int $status) {
    $this->actingAs(User::factory()->role($role)->create())
        ->get(route('admin.translations.index'))
        ->assertStatus($status);
})->with([
    'super admin' => [UserRole::SuperAdmin, 200],
    'editor' => [UserRole::Editor, 200],
    'sales' => [UserRole::Sales, 403],
    'hr' => [UserRole::Hr, 403],
]);

it('lists the missing and outdated texts per area and language', function () {
    $catalog = new TranslationCatalog;
    $title = $catalog->find('service.'.$this->service->id.'.title');

    expect($title->area)->toBe(TranslationCatalog::AREA_SERVICES)
        ->and($catalog->status($title, 'ar'))->toBe('missing');

    Translation::factory()->of($this->service, 'title', 'تطوير البرمجيات المخصصة')->create();
    expect((new TranslationCatalog)->status($title, 'ar'))->toBe('current');

    $this->service->update(['title' => 'Custom Software Development Services']);

    $catalog = new TranslationCatalog;
    expect($catalog->status($catalog->find('service.'.$this->service->id.'.title'), 'ar'))->toBe('outdated');

    $this->actingAs($this->editor)->get(route('admin.translations.index'))
        ->assertOk()
        ->assertSee('Service pages')
        ->assertSee('1 outdated');
});

it('exports only the name and short description of services without a translated page', function () {
    $other = Service::factory()->create(['is_active' => true, 'slug' => 'seo-services', 'title' => 'SEO Services', 'seo_title' => 'SEO']);

    $ids = (new TranslationCatalog)->items()->keys();

    expect($ids)->toContain('service.'.$other->id.'.title')
        ->not->toContain('service.'.$other->id.'.seo_title')
        ->toContain('service.'.$this->service->id.'.seo_title')
        ->toContain('service.'.$this->service->id.'.content.hero.heading');
});

it('exports a batch in the agreed format and records it', function () {
    $file = exportBatch();
    $heading = collect($file['items'])->firstWhere('id', 'service.'.$this->service->id.'.content.hero.heading');
    $seoTitle = collect($file['items'])->firstWhere('id', 'service.'.$this->service->id.'.seo_title');

    expect($file['export_id'])->toMatch('/^\d{4}-\d{2}-\d{2}-01$/')
        ->and($file['languages'])->toBe(['ar', 'es', 'fr', 'de'])
        ->and(count($file['items']))->toBeLessThanOrEqual(TranslationCatalog::BATCH_SIZE)
        ->and($heading['source'])->toBe('Software Built Around You')
        ->and($heading['translations'])->toBe(['ar' => '', 'es' => '', 'fr' => '', 'de' => ''])
        ->and($heading['context'])->toContain('Custom Software Development page')
        ->and($seoTitle['max_length'])->toBe(60);

    expect(TranslationExport::sole()->export_id)->toBe($file['export_id']);
});

it('lists only the languages each item still needs', function () {
    Translation::factory()->of($this->service, 'title', 'تطوير البرمجيات المخصصة')->create();

    $title = collect(exportBatch()['items'])->firstWhere('id', 'service.'.$this->service->id.'.title');

    expect(array_keys($title['translations']))->toBe(['es', 'fr', 'de']);
});

it('shows the prompt with each language\'s glossary', function () {
    GlossaryTerm::factory()->create(['locale' => 'ar', 'term' => 'Consultation', 'translation' => 'استشارة']);

    $this->actingAs($this->editor)->get(route('admin.translations.index'))
        ->assertOk()
        ->assertSee('Return the same JSON')
        ->assertSee('Consultation = استشارة');
});

it('rejects files that are not a batch exported here', function (string $contents, string $message) {
    $this->actingAs($this->editor)
        ->post(route('admin.translations.import'), ['file' => UploadedFile::fake()->createWithContent('t.json', $contents)])
        ->assertRedirect(route('admin.translations.index'))
        ->assertSessionHasErrors(['file' => $message]);
})->with([
    'invalid JSON' => ['{not json', 'The file is not valid JSON in the export format.'],
    'unknown export' => ['{"export_id":"2020-01-01-01","items":[]}', 'The file does not come from an export made here. Check its export_id.'],
]);

it('rejects files with an item that is not in the export', function () {
    $file = exportBatch();
    $file['items'][] = ['id' => 'service.999.title', 'translations' => ['ar' => 'x']];

    $this->actingAs($this->editor)
        ->post(route('admin.translations.import'), ['file' => uploadTranslations($file)])
        ->assertRedirect(route('admin.translations.index'))
        ->assertSessionHasErrors('file');
});

it('previews the translations and flags the ones with problems', function () {
    $file = exportBatch();
    $id = 'service.'.$this->service->id;

    $file = fillTranslations($file, [
        $id.'.title' => ['ar' => 'تطوير البرمجيات المخصصة', 'es' => 'Desarrollo de software a medida'],
        $id.'.content.hero.intro' => ['ar' => '<p>نبني البرمجيات.</p>'],
        $id.'.seo_title' => ['ar' => str_repeat('ب', 61)],
    ]);

    $this->service->update(['short_description' => 'Changed after the export.']);
    $file = fillTranslations($file, [$id.'.short_description' => ['ar' => 'وصف']]);

    $this->actingAs($this->editor)
        ->post(route('admin.translations.import'), ['file' => uploadTranslations($file)])
        ->assertOk()
        ->assertSee('Desarrollo de software a medida')
        ->assertSee('The HTML tags differ from the English.')
        ->assertSee('Longer than 60 characters (61).')
        ->assertSee('The English changed after the export')
        ->assertSee('3 with problems');

    expect(Translation::count())->toBe(0);
});

it('saves the reviewed translations with their fingerprint and importer, and skips problems', function () {
    $file = exportBatch();
    $id = 'service.'.$this->service->id;

    $this->actingAs($this->editor)
        ->post(route('admin.translations.confirm'), [
            'export_id' => $file['export_id'],
            'values' => [
                $id.'.title' => ['ar' => 'تطوير البرمجيات المخصصة', 'es' => 'Desarrollo de software a medida'],
                $id.'.content.hero.intro' => ['ar' => '<p>نبني <strong>البرمجيات</strong>.</p>', 'fr' => 'Sans balises'],
                'service.999.title' => ['ar' => 'غير موجود'],
            ],
        ])
        ->assertRedirect(route('admin.translations.index'))
        ->assertSessionHas('status', fn (string $status): bool => str_contains($status, 'Saved 3 translations') && str_contains($status, 'Skipped 1'));

    $title = Translation::where('field', 'title')->where('locale', 'ar')->sole();

    expect($title->value)->toBe('تطوير البرمجيات المخصصة')
        ->and($title->source_hash)->toBe(Translation::hash('Custom Software Development'))
        ->and($title->imported_by)->toBe($this->editor->id)
        ->and(Translation::where('field', 'content.hero.intro')->pluck('locale')->all())->toBe(['ar']);

    app()->setLocale('ar');
    expect(Service::find($this->service->id)->title)->toBe('تطوير البرمجيات المخصصة');
});

it('re-checks edited values before saving when asked', function () {
    $file = exportBatch();
    $id = 'service.'.$this->service->id;

    $this->actingAs($this->editor)
        ->post(route('admin.translations.confirm'), [
            'export_id' => $file['export_id'],
            'review' => '1',
            'values' => [$id.'.seo_title' => ['ar' => str_repeat('ب', 70)]],
        ])
        ->assertOk()
        ->assertSee('Longer than 60 characters (70).');

    expect(Translation::count())->toBe(0);
});

it('translates settings and legal pages through the tab', function () {
    Setting::updateOrCreate(['key' => 'core_heading'], ['value' => 'Software and Websites Built', 'group' => 'home']);
    Setting::updateOrCreate(['key' => 'legal_privacy-policy'], ['value' => '<h2>Privacy</h2><p>Text</p>', 'group' => 'legal']);

    $catalog = new TranslationCatalog;

    expect($catalog->find('setting.core_heading')->area)->toBe(TranslationCatalog::AREA_HOMEPAGE)
        ->and($catalog->find('setting.legal_privacy-policy')->area)->toBe(TranslationCatalog::AREA_LEGAL)
        ->and($catalog->find('setting.site_phone'))->toBeNull();
});

it('adds and removes glossary terms', function () {
    $this->actingAs($this->editor)
        ->post(route('admin.translations.glossary.store'), ['locale' => 'de', 'term' => 'Consultation', 'translation' => 'Beratung'])
        ->assertRedirect();

    $term = GlossaryTerm::sole();
    expect($term->only(['locale', 'term', 'translation']))->toBe(['locale' => 'de', 'term' => 'Consultation', 'translation' => 'Beratung']);

    $this->actingAs($this->editor)
        ->post(route('admin.translations.glossary.store'), ['locale' => 'de', 'term' => 'Consultation', 'translation' => 'Anders'])
        ->assertSessionHasErrors('term');

    $this->actingAs($this->editor)->delete(route('admin.translations.glossary.destroy', $term))->assertRedirect();

    expect(GlossaryTerm::count())->toBe(0);
});
