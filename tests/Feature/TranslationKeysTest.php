<?php

use App\Models\Service;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

/**
 * Every string of a language folder, keyed `group.key.sub_key`.
 *
 * @return array<string, mixed>
 */
function languageStrings(string $code): array
{
    $strings = [];

    foreach (File::files(lang_path($code)) as $file) {
        $strings += Arr::dot([$file->getFilenameWithoutExtension() => require $file->getPathname()]);
    }

    return $strings;
}

/**
 * @return list<string>
 */
function placeholdersIn(string $text): array
{
    preg_match_all('/:[a-z_]+/i', $text, $matches);
    $placeholders = array_values(array_unique($matches[0]));
    sort($placeholders);

    return $placeholders;
}

it('has every English key, with the same placeholders, in each language folder', function () {
    $english = languageStrings('en');

    expect($english)->not->toBeEmpty();

    foreach (Locales::prefixed() as $code) {
        if (! is_dir(lang_path($code))) {
            continue;
        }

        $strings = languageStrings($code);

        foreach ($english as $key => $text) {
            expect(array_key_exists($key, $strings))->toBeTrue("lang/{$code} is missing {$key}");

            if (! is_string($text)) {
                continue;
            }

            expect(placeholdersIn((string) $strings[$key]))
                ->toBe(placeholdersIn($text), "lang/{$code}: {$key} has different placeholders");
        }
    }
});

it('puts a non-breaking space before French high punctuation', function () {
    $round = json_decode(File::get(database_path('seeders/translations/fr.json')), true);
    $texts = [
        ...array_filter(languageStrings('fr'), is_string(...)),
        ...collect($round['items'])->mapWithKeys(fn (array $item): array => ['database '.$item['id'] => $item['translation']])->all(),
    ];

    expect($texts)->not->toBeEmpty();

    foreach ($texts as $key => $text) {
        $plain = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        expect(preg_match('/(?<![\x{00A0}\x{202F}])[:;!?](?=\s|$)/u', $plain))
            ->toBe(0, "French {$key} needs a non-breaking space before : ; ! or ?");
    }
});

it('has a language folder for every enabled language', function () {
    foreach (Locales::enabled() as $code) {
        expect(is_dir(lang_path($code)))->toBeTrue("lang/{$code} is missing");
    }
});

it('serves the fixed text in each language', function (string $code) {
    enableLanguages($code);

    $sendEnquiry = trans('forms.send_enquiry', [], $code);

    expect($sendEnquiry)->not->toBe(trans('forms.send_enquiry', [], 'en'));

    $this->get("/{$code}/contact")
        ->assertOk()
        ->assertSee($sendEnquiry)
        ->assertSee(trans('nav.contact_us', [], $code))
        ->assertDontSee('Send Enquiry');
})->with(['ar', 'es', 'fr', 'de']);

it('shows no raw translation keys on the translated pages', function (string $url) {
    Service::factory()->create(['is_active' => true, 'slug' => 'custom-software-development']);

    $html = $this->get($url)->assertOk()->getContent();

    preg_match_all('/\b(?:nav|footer|home|services|landing|forms|common|contact)\.[a-z_]+(?:\.[a-z0-9_-]+)*/', $html, $matches);

    expect($matches[0])->toBe([]);
})->with([
    '/',
    '/contact',
    '/services/custom-software-development',
    '/lp/website-development',
    '/legal/privacy-policy',
]);
