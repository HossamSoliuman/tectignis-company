<?php

use App\Enums\UserRole;
use App\Models\Service;
use App\Models\User;
use App\Services\Translations\TranslationCatalog;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function languagesAdmin(): User
{
    return User::factory()->role(UserRole::SuperAdmin)->create(['name' => 'Layla Admin']);
}

/**
 * Sign the admin out, so the next request is an ordinary visitor's.
 */
function asVisitor(): void
{
    auth()->forgetGuards();
}

describe('access', function () {
    it('is open to Super Admin only', function () {
        $this->actingAs(languagesAdmin())
            ->get(route('admin.languages.edit'))
            ->assertSuccessful()
            ->assertSee('Arabic')
            ->assertSee('العربية')
            ->assertSee('Preview only');
    });

    it('is closed to other admin roles', function (UserRole $role) {
        $user = User::factory()->role($role)->create();

        $this->actingAs($user)->get(route('admin.languages.edit'))->assertForbidden();
        $this->actingAs($user)->put(route('admin.languages.toggle', 'ar'), ['enabled' => '1'])->assertForbidden();
        $this->actingAs($user)->put(route('admin.languages.update'), ['detect_browser' => '0'])->assertForbidden();

        expect(Locales::isEnabled('ar'))->toBeFalse()
            ->and(Locales::detectsBrowserLanguage())->toBeTrue();
    })->with([UserRole::Editor, UserRole::Sales]);

    it('keeps the language settings off the Site Settings screen', function () {
        $editor = User::factory()->role(UserRole::Editor)->create();

        $this->actingAs($editor)
            ->get(route('admin.settings.index'))
            ->assertSuccessful()
            ->assertDontSee(Locales::SETTING_ENABLED);

        $this->actingAs($editor)
            ->put(route('admin.settings.update'), ['settings' => [Locales::SETTING_ENABLED => '["ar"]']])
            ->assertRedirect();

        expect(Locales::isEnabled('ar'))->toBeFalse();
    });
});

describe('switching languages', function () {
    it('puts a language live in routing, the switcher, hreflang and the sitemap', function () {
        $admin = languagesAdmin();

        $this->actingAs($admin)
            ->put(route('admin.languages.toggle', 'ar'), ['enabled' => '1'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Arabic is live.');

        expect(Locales::enabled())->toBe(['en', 'ar'])
            ->and(Locales::changes()['ar'])->toMatchArray(['enabled' => true, 'by' => $admin->id]);

        $this->get(route('admin.languages.edit'))->assertSee('Switched on')->assertSee('by Layla Admin');

        asVisitor();

        $this->get('/ar')->assertSuccessful();
        $this->get('/')
            ->assertSuccessful()
            ->assertSee('href="'.url('/ar').'" hreflang="ar"', false)
            ->assertSee('<link rel="alternate" hreflang="ar" href="'.url('/ar').'">', false);
        $this->get(route('sitemap'))->assertSee('<loc>'.url('/ar').'</loc>', false);
    });

    it('takes a language off the public site when switched off', function () {
        $admin = languagesAdmin();
        Locales::switchLanguage('ar', true, $admin);

        $this->actingAs($admin)
            ->put(route('admin.languages.toggle', 'ar'), ['enabled' => '0'])
            ->assertSessionHas('status', 'Arabic is back to preview only.');

        expect(Locales::enabled())->toBe(['en'])
            ->and(Locales::changes()['ar']['enabled'])->toBeFalse();

        asVisitor();

        $this->get('/ar')->assertNotFound();
        $this->get('/')->assertSuccessful()->assertDontSee('rel="alternate" hreflang', false);
        $this->get(route('sitemap'))->assertDontSee(url('/ar'), false);
    });

    it('keeps the other languages as they are', function () {
        enableLanguages('es');

        $this->actingAs(languagesAdmin())->put(route('admin.languages.toggle', 'fr'), ['enabled' => '1']);

        expect(Locales::enabled())->toBe(['en', 'es', 'fr']);
    });

    it('blocks switching on a language without its lang folder', function () {
        config(['locales.supported.it' => ['label' => 'Italian', 'name' => 'Italiano', 'dir' => 'ltr', 'og_locale' => 'it_IT']]);

        $this->actingAs(languagesAdmin())
            ->put(route('admin.languages.toggle', 'it'), ['enabled' => '1'])
            ->assertSessionHasErrors('enabled');

        expect(Locales::isEnabled('it'))->toBeFalse();
    });

    it('never switches English or an unknown language', function (string $code) {
        $this->actingAs(languagesAdmin())
            ->put(route('admin.languages.toggle', $code), ['enabled' => '0'])
            ->assertNotFound();

        expect(Locales::isEnabled('en'))->toBeTrue();
    })->with(['en', 'xx']);

    it('shows the missing database text and preview links per language', function () {
        Service::factory()->create(['is_active' => true, 'slug' => 'custom-software-development', 'title' => 'Custom Software Development']);

        $this->actingAs(languagesAdmin())
            ->get(route('admin.languages.edit'))
            ->assertSuccessful()
            ->assertSee('lang/ar')
            ->assertSee('missing')
            ->assertSee(url('/ar/services/custom-software-development'), false)
            ->assertSee(url('/de/legal/privacy-policy'), false);
    });
});

describe('translated service pages', function () {
    it('updates routing, the export and the sitemap, and sends a removed service to English', function () {
        enableLanguages('es');
        $customSoftware = Service::factory()->create(['is_active' => true, 'slug' => 'custom-software-development']);
        $seo = Service::factory()->create(['is_active' => true, 'slug' => 'seo-services']);

        $this->actingAs(languagesAdmin())
            ->put(route('admin.languages.update'), ['translated_services' => ['seo-services'], 'detect_browser' => '1'])
            ->assertSessionHas('status');

        expect(Locales::translatedServices())->toBe(['seo-services']);

        $catalog = new TranslationCatalog;

        expect($catalog->find("service.{$seo->id}.title")->area)->toBe(TranslationCatalog::AREA_SERVICES)
            ->and($catalog->find("service.{$customSoftware->id}.title")->area)->toBe(TranslationCatalog::AREA_MENUS);

        asVisitor();

        $this->get('/es/services/seo-services')->assertSuccessful();
        $this->get('/es/services/custom-software-development')
            ->assertMovedPermanently()
            ->assertRedirect(url('/services/custom-software-development'));
        $this->get(route('sitemap'))
            ->assertSee('<loc>'.url('/es/services/seo-services').'</loc>', false)
            ->assertDontSee(url('/es/services/custom-software-development'), false);
    });

    it('only accepts active services', function () {
        Service::factory()->create(['is_active' => false, 'slug' => 'retired-service']);

        $this->actingAs(languagesAdmin())
            ->put(route('admin.languages.update'), ['translated_services' => ['retired-service'], 'detect_browser' => '1'])
            ->assertSessionHasErrors('translated_services.0');

        expect(Locales::translatedServices())->toBe(['custom-software-development', 'website-design-development']);
    });

    it('can leave every service page in English', function () {
        $this->actingAs(languagesAdmin())
            ->put(route('admin.languages.update'), ['detect_browser' => '1'])
            ->assertSessionHasNoErrors();

        expect(Locales::translatedServices())->toBe([]);
    });
});

describe('browser detection', function () {
    it('never redirects a first visit while detection is off', function () {
        enableLanguages('ar');

        $this->actingAs(languagesAdmin())
            ->put(route('admin.languages.update'), ['detect_browser' => '0'])
            ->assertSessionHasNoErrors();

        expect(Locales::detectsBrowserLanguage())->toBeFalse();

        asVisitor();

        $this->get('/', ['Accept-Language' => 'ar'])->assertSuccessful();
    });

    it('redirects a first visit to the preferred live language while detection is on', function () {
        enableLanguages('ar');

        $this->get('/', ['Accept-Language' => 'ar'])->assertRedirect(url('/ar'));
    });
});
