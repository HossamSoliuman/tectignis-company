<?php

use App\Models\Service;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['locales.supported.ar.enabled' => true]);
});

function serviceWithContent(): Service
{
    return Service::factory()->create([
        'is_active' => true,
        'slug' => 'custom-software-development',
        'title' => 'Custom Software Development',
        'short_description' => 'Software built around your business.',
        'content' => [
            'hero' => ['heading' => 'Custom Software Built Around You', 'intro' => 'English intro.', 'bullets' => ['First bullet', 'Second bullet']],
            'features_strip' => ['enabled' => true, 'items' => [['icon' => 'services/icon.webp', 'label' => 'Scalable Architecture']]],
            'related' => ['enabled' => true, 'service_ids' => [2, 3]],
        ],
    ]);
}

it('serves translated fields in the current language and English otherwise', function () {
    $service = serviceWithContent();
    Translation::factory()->of($service, 'title', 'تطوير البرمجيات المخصصة')->create();

    app()->setLocale('ar');
    expect(Service::find($service->id)->title)->toBe('تطوير البرمجيات المخصصة')
        ->and(Service::find($service->id)->short_description)->toBe('Software built around your business.');

    app()->setLocale('en');
    expect(Service::find($service->id)->title)->toBe('Custom Software Development');
});

it('translates nested content by path and keeps the English structure', function () {
    $service = serviceWithContent();
    Translation::factory()->of($service, 'content.hero.heading', 'برمجيات مخصصة')->create();
    Translation::factory()->of($service, 'content.hero.bullets.1', 'النقطة الثانية')->create();
    Translation::factory()->of($service, 'content.features_strip.items.0.label', 'بنية قابلة للتوسع')->create();

    app()->setLocale('ar');
    $content = Service::find($service->id)->content;

    expect($content['hero']['heading'])->toBe('برمجيات مخصصة')
        ->and($content['hero']['intro'])->toBe('English intro.')
        ->and($content['hero']['bullets'])->toBe(['First bullet', 'النقطة الثانية'])
        ->and($content['features_strip']['items'][0])->toBe(['icon' => 'services/icon.webp', 'label' => 'بنية قابلة للتوسع'])
        ->and($content['features_strip']['enabled'])->toBeTrue()
        ->and($content['related']['service_ids'])->toBe([2, 3]);
});

it('ignores a translation whose path no longer exists in the English content', function () {
    $service = serviceWithContent();
    Translation::factory()->of($service, 'content.hero.bullets.5', 'نقطة محذوفة')->create();

    app()->setLocale('ar');

    expect(Service::find($service->id)->content['hero']['bullets'])->toBe(['First bullet', 'Second bullet']);
});

it('lists only the text a record holds as translation sources', function () {
    $sources = serviceWithContent()->translationSources();

    expect($sources)
        ->toHaveKey('title', 'Custom Software Development')
        ->toHaveKey('content.hero.bullets.0', 'First bullet')
        ->toHaveKey('content.features_strip.items.0.label', 'Scalable Architecture')
        ->not->toHaveKey('content.features_strip.items.0.icon')
        ->not->toHaveKey('content.related.service_ids.0');
});

it('removes a record\'s translations when the record is deleted', function () {
    $service = serviceWithContent();
    Translation::factory()->of($service, 'title', 'عنوان')->create();

    $service->delete();

    expect(Translation::count())->toBe(0);
});

it('serves the translatable settings in the current language', function () {
    $heading = Setting::updateOrCreate(['key' => 'core_heading'], ['value' => 'Software and Websites Built', 'group' => 'home']);
    $phone = Setting::updateOrCreate(['key' => 'site_phone'], ['value' => '+91 99999 00000', 'group' => 'general']);
    Translation::factory()->of($heading, 'value', 'برمجيات ومواقع')->create();
    Translation::factory()->of($phone, 'value', 'not a text setting')->create();

    app()->setLocale('ar');
    expect(Setting::get('core_heading'))->toBe('برمجيات ومواقع')
        ->and(Setting::get('site_phone'))->toBe('+91 99999 00000');

    app()->setLocale('en');
    expect(Setting::get('core_heading'))->toBe('Software and Websites Built');
});

it('keeps the cached homepage per language and refreshes it when a translation is saved', function () {
    $stat = Stat::factory()->create(['key' => 'projects', 'value' => '150+', 'label' => 'Projects Delivered', 'is_active' => true]);

    $this->get('/')->assertOk()->assertSee('Projects Delivered');
    $this->get('/ar')->assertOk()->assertSee('Projects Delivered');

    Translation::factory()->of($stat, 'label', 'مشروع منجز')->create();

    $this->get('/ar')->assertOk()->assertSee('مشروع منجز');
    $this->get('/')->assertOk()->assertSee('Projects Delivered')->assertDontSee('مشروع منجز');
});

it('renders a translated service page from the translations', function () {
    $service = serviceWithContent();
    Translation::factory()->of($service, 'content.hero.heading', 'برمجيات مخصصة لأعمالك')->create();

    $this->get('/ar/services/custom-software-development')
        ->assertOk()
        ->assertSee('برمجيات مخصصة لأعمالك');

    $this->get('/services/custom-software-development')
        ->assertOk()
        ->assertDontSee('برمجيات مخصصة لأعمالك');
});
