<?php

use App\Models\LandingPage;
use App\Models\Service;
use App\Services\SeoCrawler;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

/**
 * A page of the fake site the crawler reads in these tests.
 *
 * @param  array<string, string>  $hreflang
 */
function crawledPage(string $lang, string $title, string $canonical, array $hreflang = [], string $head = '', string $main = '<h1>Heading</h1>'): string
{
    $links = collect($hreflang)
        ->map(fn (string $href, string $code): string => '<link rel="alternate" hreflang="'.$code.'" href="'.$href.'">')
        ->implode('');

    return '<!DOCTYPE html><html lang="'.$lang.'"><head><meta charset="utf-8"><title>'.$title.'</title>'
        .'<meta name="description" content="About '.$title.'"><link rel="canonical" href="'.$canonical.'">'.$links.$head
        .'</head><body><main>'.$main.'</main></body></html>';
}

it('passes the site’s own pages in every language', function () {
    enableLanguages(...Locales::prefixed());
    Locales::saveTranslatedServices(['custom-software-development']);
    $service = Service::factory()->create(['is_active' => true, 'slug' => 'custom-software-development']);
    LandingPage::factory()->for($service)->create(['slug' => 'custom-software']);

    // Answer the crawler's requests from the application itself.
    Http::fake(function (Request $request) {
        $response = $this->withHeader('User-Agent', $request->header('User-Agent')[0])->get($request->url());

        return Http::response($response->getContent(), $response->getStatusCode(), $response->headers->all());
    });

    $this->artisan('seo:crawl', ['url' => url('/')])
        ->expectsOutputToContain('Crawled ')
        ->doesntExpectOutputToContain('error ')
        ->assertSuccessful();

    Http::assertSent(fn (Request $request): bool => $request->url() === url('/de/services/custom-software-development')
        && $request->header('User-Agent')[0] === SeoCrawler::USER_AGENT);
});

it('reports what keeps pages out of the index or under the wrong language', function () {
    $site = 'https://example.test';
    $home = $site.'/';
    $arabic = $site.'/ar';
    $german = $site.'/de';

    $pages = [
        $site.'/robots.txt' => "User-agent: *\nDisallow: /private\nUser-agent: Googlebot Allow: /",
        $site.'/sitemap.xml' => '<?xml version="1.0" encoding="UTF-8"?>'
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'
            .'<url><loc>'.$home.'</loc><xhtml:link rel="alternate" hreflang="en" href="'.$home.'"/><xhtml:link rel="alternate" hreflang="ar" href="'.$arabic.'"/></url>'
            .'<url><loc>'.$arabic.'</loc></url>'
            .'<url><loc>'.$german.'</loc></url>'
            .'<url><loc>'.$site.'/private</loc></url>'
            .'<url><loc>'.$site.'/old</loc></url>'
            .'</urlset>',
        $home => crawledPage('en', 'Home', $home, ['en' => $home, 'ar' => $arabic, 'de' => $german, 'fr' => $site.'/fr', 'x-default' => $home], main: '<h1>Home</h1><h3>Skipped</h3><img src="/team.webp">'),
        // Untranslated, indexed as English and pointing search engines elsewhere.
        $arabic => crawledPage('en', 'Home', $home, ['ar' => $arabic, 'en' => $home], '<meta name="robots" content="noindex, nofollow">'),
        // Never links back to the English page.
        $german => crawledPage('de', 'Startseite', $german, ['de' => $german, 'x-default' => $home]),
        $site.'/private' => crawledPage('en', 'Private', $site.'/private'),
    ];

    Http::fake(fn (Request $request) => match (true) {
        $request->url() === $site.'/old' => Http::response('', 301, ['Location' => $site.'/new']),
        isset($pages[$request->url()]) => Http::response($pages[$request->url()]),
        default => Http::response('', 404),
    });

    $this->artisan('seo:crawl', ['url' => $site])
        ->expectsOutputToContain('Crawled 5 pages from https://example.test/sitemap.xml.')
        ->expectsOutputToContain('robots.txt does not list the sitemap')
        ->expectsOutputToContain('robots.txt line 3 holds more than one directive')
        ->expectsOutputToContain('Heading levels skip: h1 → h3')
        ->expectsOutputToContain('Images without alt text: /team.webp')
        ->expectsOutputToContain('The page and the sitemap list different hreflang alternates')
        ->expectsOutputToContain('hreflang "fr" points to https://example.test/fr, which is not in the sitemap')
        ->expectsOutputToContain('https://example.test/de does not link back to this page as hreflang "en"')
        ->expectsOutputToContain('Marked noindex')
        ->expectsOutputToContain('Canonical points to https://example.test/')
        ->expectsOutputToContain('<html lang="en">, expected "ar"')
        ->expectsOutputToContain('No x-default hreflang')
        ->expectsOutputToContain('Same title as the English page')
        ->expectsOutputToContain('The sitemap lists no hreflang alternates for this page')
        ->expectsOutputToContain('Blocked by robots.txt')
        ->expectsOutputToContain('Redirects (HTTP 301) to https://example.test/new')
        ->assertFailed();
});

it('stops when the sitemap cannot be read', function () {
    Http::fake(['*' => Http::response('', 500)]);

    $this->artisan('seo:crawl', ['url' => 'https://example.test'])
        ->expectsOutputToContain('The sitemap at https://example.test/sitemap.xml returns HTTP 500.')
        ->assertFailed();
});
