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
            expect($strings)->toHaveKey($key, "lang/{$code} is missing {$key}");
            expect(placeholdersIn((string) $strings[$key]))
                ->toBe(placeholdersIn((string) $text), "lang/{$code}: {$key} has different placeholders");
        }
    }
});

it('has a language folder for every enabled language', function () {
    foreach (Locales::enabled() as $code) {
        expect(is_dir(lang_path($code)))->toBeTrue("lang/{$code} is missing");
    }
});

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
