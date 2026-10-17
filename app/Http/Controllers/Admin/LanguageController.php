<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateLanguageSettingsRequest;
use App\Models\LandingPage;
use App\Models\Service;
use App\Models\User;
use App\Services\Translations\TranslationCatalog;
use App\Support\Locales;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Settings → Languages. Super Admin only. Every language setting that changes
 * without a deploy: which languages are live, which service pages have a
 * version in every language, and first-visit browser detection.
 */
class LanguageController extends Controller
{
    public function edit(TranslationCatalog $catalog): View
    {
        $summary = $catalog->summary();
        $changes = Locales::changes();
        $changedBy = User::whereIn('id', array_column($changes, 'by'))->pluck('name', 'id');
        $services = Service::active()->ordered()->get(['id', 'slug', 'title']);
        $landingPages = LandingPage::active()->ordered()->get(['id', 'slug', 'title']);

        $languages = [];

        foreach (Locales::codes() as $code) {
            $change = $changes[$code] ?? null;

            $languages[$code] = [
                'label' => Locales::label($code),
                'name' => Locales::name($code),
                'dir' => Locales::direction($code),
                'is_default' => Locales::isDefault($code),
                'enabled' => Locales::isEnabled($code),
                'has_lang_files' => Locales::hasLangFiles($code),
                'missing' => collect($summary)->sum(fn (array $counts): int => $counts[$code]['missing'] ?? 0),
                'outdated' => collect($summary)->sum(fn (array $counts): int => $counts[$code]['outdated'] ?? 0),
                'previews' => Locales::isDefault($code) ? [] : $this->previewLinks($code, $services, $landingPages),
                'changed_by' => $change ? $changedBy->get($change['by']) : null,
                'changed_at' => $change ? Carbon::parse($change['at'])->timezone(config('app.timezone')) : null,
            ];
        }

        return view('admin.languages.edit', [
            'languages' => $languages,
            'services' => $services,
            'translatedServices' => Locales::translatedServices(),
            'detectBrowser' => Locales::detectsBrowserLanguage(),
        ]);
    }

    /**
     * Save the translated service pages and browser detection.
     */
    public function update(UpdateLanguageSettingsRequest $request): RedirectResponse
    {
        Locales::saveTranslatedServices($request->validated('translated_services', []));
        Locales::saveBrowserDetection($request->boolean('detect_browser'));
        TranslationCatalog::forgetPendingCount();

        return back()->with('status', 'Language settings saved.');
    }

    /**
     * Switch a language on for visitors, or back to preview only. English is
     * always on, and a language can't go live without its lang/ folder.
     */
    public function toggle(Request $request, string $language): RedirectResponse
    {
        abort_unless(in_array($language, Locales::prefixed(), true), 404);

        $request->validate(['enabled' => ['required', 'boolean']]);
        $enabled = $request->boolean('enabled');

        if ($enabled && ! Locales::hasLangFiles($language)) {
            return back()->withErrors([
                'enabled' => Locales::label($language).' can\'t be switched on until lang/'.$language.' exists.',
            ]);
        }

        Locales::switchLanguage($language, $enabled, $request->user());

        return back()->with('status', Locales::label($language).($enabled ? ' is live.' : ' is back to preview only.'));
    }

    /**
     * Every translated page in the language, for checking it before and after
     * it goes live.
     *
     * @param  Collection<int, Service>  $services
     * @param  Collection<int, LandingPage>  $landingPages
     * @return array<string, string> label => URL
     */
    private function previewLinks(string $code, Collection $services, Collection $landingPages): array
    {
        $links = [
            'Homepage' => Locales::route('home', [], $code),
            'Contact' => Locales::route('contact', [], $code),
        ];

        foreach ($services as $service) {
            if (Locales::isTranslatedPage('services.show', $service->slug)) {
                $links[$service->title] = Locales::route('services.show', $service->slug, $code);
            }
        }

        foreach ($landingPages as $landingPage) {
            $links['Landing: '.$landingPage->title] = Locales::route('landing-pages.show', $landingPage->slug, $code);
        }

        foreach (Locales::translatedPages()['legal.show'] as $slug) {
            $links[Str::headline($slug)] = Locales::route('legal.show', $slug, $code);
        }

        return $links;
    }
}
