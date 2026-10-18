<?php

use App\Enums\UserRole;
use App\Models\LandingPage;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Support\CompanyProfile;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;

uses(RefreshDatabase::class);

beforeEach(function () {
    // The deploy migration seeds the two priority landing pages; start each test empty.
    LandingPage::query()->delete();
});

function seoService(): Service
{
    Locales::saveTranslatedServices(['custom-software-development']);

    return Service::factory()->create([
        'is_active' => true,
        'slug' => 'custom-software-development',
        'title' => 'Custom Software Development',
        'seo_description' => 'Bespoke software for growing businesses.',
        'content' => [
            'faq' => ['enabled' => true, 'items' => [['question' => 'How long does a project take?', 'answer' => 'Most projects take <strong>8–16</strong> weeks.']]],
        ],
    ]);
}

/**
 * Each sitemap entry with its hreflang alternates.
 *
 * @return array<string, array<string, string>>
 */
function sitemapAlternates(TestResponse $response): array
{
    $document = new DOMDocument;
    $document->loadXML($response->getContent());

    $xpath = new DOMXPath($document);
    $xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    $xpath->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');

    $entries = [];

    foreach ($xpath->query('//s:url') as $node) {
        $alternates = [];

        foreach ($xpath->query('xhtml:link', $node) as $link) {
            $alternates[$link->getAttribute('hreflang')] = $link->getAttribute('href');
        }

        $entries[$xpath->evaluate('string(s:loc)', $node)] = $alternates;
    }

    return $entries;
}

/**
 * The JSON-LD blocks of a page, by @type.
 *
 * @return array<string, array<string, mixed>>
 */
function jsonLd(TestResponse $response): array
{
    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $response->getContent(), $matches);

    return collect($matches[1])
        ->map(fn (string $json): array => json_decode($json, true, flags: JSON_THROW_ON_ERROR))
        ->keyBy('@type')
        ->all();
}

describe('sitemap', function () {
    it('lists every language version of a translated page with its hreflang alternates', function () {
        enableLanguages('ar', 'es');
        seoService();
        Service::factory()->create(['is_active' => true, 'slug' => 'seo-services']);

        $entries = sitemapAlternates($this->get(route('sitemap'))->assertOk());

        $serviceAlternates = [
            'en' => url('/services/custom-software-development'),
            'ar' => url('/ar/services/custom-software-development'),
            'es' => url('/es/services/custom-software-development'),
            'x-default' => url('/services/custom-software-development'),
        ];

        expect($entries[url('/services/custom-software-development')])->toBe($serviceAlternates)
            ->and($entries[url('/ar/services/custom-software-development')])->toBe($serviceAlternates)
            ->and($entries[url('/es/services/custom-software-development')])->toBe($serviceAlternates)
            ->and($entries[url('/')])->toBe(['en' => url('/'), 'ar' => url('/ar'), 'es' => url('/es'), 'x-default' => url('/')])
            ->and($entries[url('/ar/legal/privacy-policy')])->toHaveKeys(['en', 'ar', 'es', 'x-default'])
            ->and($entries[url('/services/seo-services')])->toBe([])
            ->and($entries[url('/legal/refund-policy')])->toBe([])
            ->and($entries[url('/about')])->toBe([]);
    });

    it('lists no alternates while English is the only language', function () {
        seoService();

        $this->get(route('sitemap'))->assertOk()->assertDontSee('xhtml:link', false);
    });
});

describe('robots', function () {
    it('points crawlers to the sitemap', function () {
        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('Sitemap: '.url('/sitemap.xml'));
    });

    it('puts robots.txt directives saved on one line back on their own lines', function () {
        Setting::set('robots_txt', 'User-agent: *Allow: / Disallow: /admin', 'seo');

        (require database_path('migrations/2026_10_18_103803_split_robots_txt_directives_onto_lines.php'))->up();

        expect(Setting::get('robots_txt'))->toBe("User-agent: *\nAllow: /\nDisallow: /admin");

        $this->get(route('robots'))->assertOk()->assertSeeText("User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: ".url('/sitemap.xml'), false);
    });

    it('leaves a robots.txt with one directive per line alone', function () {
        Setting::set('robots_txt', "User-agent: *\nDisallow:", 'seo');

        (require database_path('migrations/2026_10_18_103803_split_robots_txt_directives_onto_lines.php'))->up();

        expect(Setting::get('robots_txt'))->toBe("User-agent: *\nDisallow:");
    });

    it('edits robots.txt in a field that keeps its line breaks', function () {
        Setting::set('robots_txt', "User-agent: *\nAllow: /", 'seo');

        $this->actingAs(User::factory()->role(UserRole::Editor)->create())
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('<textarea name="settings[robots_txt]"', false);
    });

    it('gives a page one robots tag, with its own directives', function () {
        $response = $this->withSession(['enquiry_submitted' => ['name' => 'Jane Doe']])
            ->get(route('contact.thank-you'))
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);

        expect(substr_count($response->getContent(), '<meta name="robots"'))->toBe(1);

        $this->get(route('contact'))->assertSee('<meta name="robots" content="index, follow">', false);
    });
});

describe('structured data', function () {
    it('describes a service page as a Service with its FAQs, in the page language', function () {
        enableLanguages('ar');
        seoService();

        $schema = jsonLd($this->get('/ar/services/custom-software-development')->assertOk());

        expect($schema['Service'])->toMatchArray([
            'name' => 'Custom Software Development',
            'description' => 'Bespoke software for growing businesses.',
            'url' => url('/ar/services/custom-software-development'),
            'provider' => ['@type' => 'Organization', '@id' => url('/').'#organization', 'name' => app(CompanyProfile::class)->name(), 'url' => url('/')],
            'areaServed' => ['US', 'AE', 'GB', 'CA', 'EU', 'IN'],
        ])
            ->and($schema['FAQPage']['mainEntity'])->toBe([[
                '@type' => 'Question',
                'name' => 'How long does a project take?',
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Most projects take 8–16 weeks.'],
            ]])
            ->and($schema['Organization']['@id'])->toBe(url('/').'#organization')
            ->and($schema['WebSite']['inLanguage'])->toBe(['en', 'ar']);
    });

    it('describes a landing page as its service with its FAQs', function () {
        $service = seoService();
        $page = LandingPage::factory()->for($service)->create([
            'slug' => 'custom-software',
            'content' => ['faq' => ['items' => [['question' => 'Do you work with clients outside India?', 'answer' => 'Yes.']]]],
        ]);

        $schema = jsonLd($this->get(route('landing-pages.show', $page->slug))->assertOk());

        expect($schema['Service']['name'])->toBe('Custom Software Development')
            ->and($schema['Service']['url'])->toBe(route('landing-pages.show', $page->slug))
            ->and($schema['FAQPage']['mainEntity'][0]['name'])->toBe('Do you work with clients outside India?')
            ->and($schema)->toHaveKey('Organization');
    });
});

describe('headings', function () {
    it('keeps one h1 on a legal page whose document has its own title', function () {
        Setting::set('legal_privacy-policy', '<h1>Privacy Policy</h1><h2>1. Information We Collect</h2>');

        $response = $this->get(route('legal.show', 'privacy-policy'))
            ->assertOk()
            ->assertSee('<h2 data-legal-title>Privacy Policy</h2>', false);

        expect(substr_count($response->getContent(), '<h1'))->toBe(1);
    });
});
