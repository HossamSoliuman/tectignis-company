<?php

use App\Enums\UserRole;
use App\Models\LandingPage;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function translatedService(): Service
{
    return Service::factory()->create(['is_active' => true, 'slug' => 'custom-software-development']);
}

describe('routing', function () {
    it('keeps the English URLs unprefixed', function () {
        expect(route('home'))->toBe(url('/'))
            ->and(route('contact'))->toBe(url('/contact'));

        $this->get('/')->assertOk()->assertSee('<html class="no-js" lang="en" dir="ltr">', false);
    });

    it('returns 404 for a language that is not switched on', function () {
        $this->get('/ar')->assertNotFound();
        $this->get('/ar/contact')->assertNotFound();
    });

    it('serves an enabled language under its prefix with its direction', function () {
        enableLanguages('ar', 'es');

        $this->get('/ar')->assertOk()->assertSee('<html class="no-js" lang="ar" dir="rtl">', false);
        $this->get('/es/contact')->assertOk()->assertSee('<html class="no-js" lang="es" dir="ltr">', false);
    });

    it('lets CMS users preview a disabled language without it being indexed', function () {
        $editor = User::factory()->role(UserRole::Editor)->create();

        $this->actingAs($editor)->get('/ar')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    });

    it('does not let other admin roles preview a disabled language', function () {
        $sales = User::factory()->role(UserRole::Sales)->create();

        $this->actingAs($sales)->get('/ar')->assertNotFound();
    });

    it('serves only the translated services under a language prefix', function () {
        enableLanguages('ar');
        translatedService();
        Service::factory()->create(['is_active' => true, 'slug' => 'seo-services']);

        $this->get('/ar/services/custom-software-development')->assertOk();
        $this->get('/ar/services/seo-services?gclid=abc123')
            ->assertMovedPermanently()
            ->assertRedirect(url('/services/seo-services').'?gclid=abc123');
        $this->get('/services/seo-services')->assertOk();
    });

    it('serves the landing pages and the legal pages in other languages', function () {
        enableLanguages('fr');
        LandingPage::where('slug', 'website-development')->update(['is_active' => true]);

        $this->get('/fr/lp/website-development')->assertOk()->assertSee('lang="fr"', false);
        $this->get('/fr/legal/privacy-policy')->assertOk();
        $this->get('/fr/legal/refund-policy')->assertMovedPermanently()->assertRedirect(url('/legal/refund-policy'));
    });

    it('does not pass the language to controllers as a route parameter', function () {
        enableLanguages('de');
        translatedService();

        $this->get('/de/services/custom-software-development')->assertOk();
    });
});

describe('links', function () {
    it('links translated pages in the current language and the rest in English', function () {
        app()->setLocale('ar');

        expect(localized_route('home'))->toBe(url('/ar'))
            ->and(localized_route('services.show', 'custom-software-development'))->toBe(url('/ar/services/custom-software-development'))
            ->and(localized_route('services.show', 'seo-services'))->toBe(url('/services/seo-services'))
            ->and(localized_route('blog.index'))->toBe(url('/blog'))
            ->and(localized_route('home', locale: 'en'))->toBe(url('/'));
    });

    it('keeps the visitor on the same page when switching language', function () {
        enableLanguages('ar', 'es');
        translatedService();

        $this->get('/ar/services/custom-software-development')
            ->assertOk()
            ->assertSee('href="'.url('/services/custom-software-development').'" hreflang="en"', false)
            ->assertSee('href="'.url('/es/services/custom-software-development').'" hreflang="es"', false);
    });

    it('sends the switcher to the chosen language homepage from an English-only page', function () {
        enableLanguages('ar');

        $this->get('/about')
            ->assertOk()
            ->assertSee('href="'.url('/ar').'" hreflang="ar"', false);
    });

    it('hides the switcher while English is the only language', function () {
        $this->get('/')->assertOk()->assertDontSee('tx-lang', false);
    });
});

describe('language cookie and detection', function () {
    it('remembers the language of a translated page in a cookie', function () {
        enableLanguages('ar');

        $this->get('/ar')->assertCookie(Locales::COOKIE, 'ar');
    });

    it('sends a first-time visitor to the language their browser prefers, keeping the query string', function () {
        enableLanguages('ar');

        $this->get('/lp/website-development?gclid=abc123&utm_source=google', ['Accept-Language' => 'ar-AE,ar;q=0.9,en;q=0.8'])
            ->assertRedirect(url('/ar/lp/website-development').'?gclid=abc123&utm_source=google');
    });

    it('does not redirect after the visitor has chosen a language', function () {
        enableLanguages('ar');

        $this->withCookie(Locales::COOKIE, 'en')
            ->get('/', ['Accept-Language' => 'ar'])
            ->assertOk();
    });

    it('never redirects search engine bots', function () {
        enableLanguages('ar');

        $this->get('/', ['Accept-Language' => 'ar', 'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1)'])
            ->assertOk();
    });

    it('does not redirect to a language that is not switched on', function () {
        $this->get('/', ['Accept-Language' => 'ar'])->assertOk();
    });

    it('does not redirect from pages without translations', function () {
        enableLanguages('ar');

        $this->get('/about', ['Accept-Language' => 'ar'])->assertOk();
    });
});

describe('SEO', function () {
    it('lists hreflang alternates for enabled languages with an English x-default', function () {
        enableLanguages('ar', 'de');

        $this->get('/ar')
            ->assertOk()
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url('/').'">', false)
            ->assertSee('<link rel="alternate" hreflang="ar" href="'.url('/ar').'">', false)
            ->assertSee('<link rel="alternate" hreflang="de" href="'.url('/de').'">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="'.url('/').'">', false)
            ->assertDontSee('hreflang="es"', false)
            ->assertSee('<link rel="canonical" href="'.url('/ar').'">', false);
    });

    it('has no hreflang alternates on pages without translations or with English alone', function () {
        enableLanguages('ar');

        $this->get('/about')->assertOk()->assertDontSee('rel="alternate" hreflang', false);

        Setting::set(Locales::SETTING_ENABLED, '[]', Locales::SETTINGS_GROUP);

        $this->get('/')->assertOk()->assertDontSee('rel="alternate" hreflang', false);
    });

    it('adds the translated pages to the sitemap for enabled languages only', function () {
        enableLanguages('es');
        translatedService();

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee('<loc>'.url('/es').'</loc>', false)
            ->assertSee('<loc>'.url('/es/services/custom-software-development').'</loc>', false)
            ->assertDontSee(url('/ar'), false);
    });
});

describe('enquiry form', function () {
    it('shows the calling code picker country names in the page language', function () {
        enableLanguages('ar');

        $this->get('/ar/contact')
            ->assertOk()
            ->assertSee('data-iso="AE" data-dial="+971" data-label="الإمارات العربية المتحدة"', false);
    })->skip(! extension_loaded('intl'), 'The intl extension is not installed.');

    it('shows the English country names on the English site', function () {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('data-iso="AE" data-dial="+971" data-label="United Arab Emirates"', false);
    });
});

describe('leads', function () {
    it('stores the language the enquiry was submitted in', function () {
        enableLanguages('ar');

        $this->post('/ar/contact', validEnquiry())
            ->assertRedirect(url('/ar/thank-you'));

        expect(Lead::sole()->language)->toBe('ar');
    });

    it('stores English for enquiries from the English site', function () {
        $this->post(route('contact.submit'), validEnquiry())->assertRedirect(route('contact.thank-you'));

        expect(Lead::sole()->language)->toBe('en');
    });
});

describe('right-to-left', function () {
    it('serves Arabic the mirrored stylesheets and an Arabic font', function () {
        enableLanguages('ar');

        $this->get('/ar')
            ->assertOk()
            ->assertSee('assets/css/vendor/vendor.rtl.min.css', false)
            ->assertSee('assets/css/style.rtl.css', false)
            ->assertSee('assets/css/custom.rtl.css', false)
            ->assertSee('assets/css/rtl.css', false)
            ->assertSee('family=IBM+Plex+Sans+Arabic', false)
            ->assertDontSee('assets/css/custom.css', false)
            ->assertDontSee('family=Inter', false);
    });

    it('keeps the left-to-right stylesheets for left-to-right languages', function (string $url) {
        enableLanguages('es');

        $this->get($url)
            ->assertOk()
            ->assertSee('assets/css/vendor/vendor.min.css', false)
            ->assertSee('assets/css/custom.css', false)
            ->assertSee('family=Inter', false)
            ->assertDontSee('rtl.css', false)
            ->assertDontSee('IBM+Plex+Sans+Arabic', false);
    })->with(['English' => '/', 'Spanish' => '/es']);

    it('keeps each mirrored stylesheet in step with its source', function (string $source, string $mirror) {
        $sourceSha1 = sha1(str_replace("\r\n", "\n", file_get_contents(public_path($source))));
        preg_match('/source-sha1: ([0-9a-f]{40})/', (string) file_get_contents(public_path($mirror), length: 1024), $banner);

        expect($banner[1] ?? null)->toBe($sourceSha1, "{$mirror} is out of date: run npm run build.");
    })->with([
        'vendor' => ['assets/css/vendor/vendor.min.css', 'assets/css/vendor/vendor.rtl.min.css'],
        'style' => ['assets/css/style.css', 'assets/css/style.rtl.css'],
        'custom' => ['assets/css/custom.css', 'assets/css/custom.rtl.css'],
    ]);

    it('keeps phone numbers left-to-right on Arabic pages', function () {
        enableLanguages('ar');

        $this->get('/ar/contact')
            ->assertOk()
            ->assertSee('dir="ltr" data-phone-field', false)
            ->assertSee('<span class="phone-field__code" dir="ltr">+971</span>', false);
    });
});
