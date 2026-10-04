<?php

use App\Enums\Pillar;
use App\Models\Capability;
use App\Models\CaseStudy;
use App\Models\Industry;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\TechStack;
use App\Support\CompanyProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('maps every capability category onto exactly one pillar', function () {
    foreach (Capability::CATEGORIES as $category) {
        expect(Pillar::forCapabilityCategory($category))->toBeInstanceOf(Pillar::class);
    }

    expect(Pillar::forCapabilityCategory('business_application'))->toBe(Pillar::SoftwareSaas)
        ->and(Pillar::forCapabilityCategory('cloud_security'))->toBe(Pillar::CloudCybersecurity)
        ->and(Pillar::forCapabilityCategory('unknown'))->toBeNull();
});

it('renders the header navigation with the four pillar mega-menu and primary CTAs', function () {
    $capability = Capability::factory()->create(['category' => 'ai_automation', 'show_in_menu' => true]);
    Service::factory()->create(['capability_id' => $capability->id, 'title' => 'Agentic Workflow Automation']);
    Industry::factory()->create(['name' => 'Aerospace Testing', 'slug' => 'aerospace-testing']);

    $response = $this->get(route('contact'))->assertOk();

    $response->assertSeeInOrder(['>Solutions<', '>Industries<', '>Our Work<', '>Resources<', '>Company<', '>Contact<'], false)
        ->assertSee('Book a Technical Consultation')
        ->assertSee('Get a Quote')
        ->assertSee('Agentic Workflow Automation')
        ->assertSee('Aerospace Testing');

    foreach (Pillar::cases() as $pillar) {
        $response->assertSee($pillar->label());
    }
});

it('renders the homepage sections in the specified order', function () {
    $capability = Capability::factory()->create(['slug' => 'software-development', 'category' => 'software_development']);
    CaseStudy::factory()->create(['title' => 'Warehouse Platform Rebuild']);
    Industry::factory()->create(['name' => 'Precision Engineering']);
    ProcessStep::factory()->create(['title' => 'Discovery']);
    TechStack::factory()->create(['name' => 'Laravel', 'category' => 'backend', 'show_on_home' => true]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<h1 class="tx-hero__title">', false)
        ->assertSeeInOrder([
            'tx-hero',
            'Four Pillars. One Accountable Technology Partner.',
            'Why Businesses Choose',
            'Warehouse Platform Rebuild',
            'Precision Engineering',
            'Discovery',
            'Backend',
            'Delivering Technology Solutions Across',
            'tx-lead-cta',
            'tx-footer',
        ], false)
        ->assertSee(route('capabilities.show', $capability->slug));
});

it('shows only one H1 on the homepage', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect(substr_count($html, '<h1'))->toBe(1);
});

it('reads company statistics from the single master source on every page', function () {
    Stat::factory()->create(['key' => 'projects', 'value' => '987+', 'label' => 'Projects Delivered', 'is_active' => true]);

    $this->get(route('home'))->assertSee('987+');
    $this->get(route('about'))->assertSee('987+')->assertDontSee('350+');
    $this->get(route('contact'))->assertSee('987+')->assertDontSee('350+');
});

it('renders contact details from the company profile in the header, footer and schema', function () {
    Setting::set('site_phone', '+91 1112223334');
    Setting::set('site_email', 'hello@example.test');
    Setting::set('company_gstin', 'GSTIN-TEST-123');

    $html = $this->get(route('home'))->assertOk()->getContent();

    expect(substr_count($html, '+91 1112223334'))->toBeGreaterThanOrEqual(3)
        ->and($html)->toContain('hello@example.test')
        ->and($html)->toContain('GSTIN-TEST-123')
        ->and($html)->toContain('"telephone": "+91 1112223334"');
});

it('renders the footer columns from the specification', function () {
    $response = $this->get(route('home'))->assertOk();

    $response->assertSeeInOrder(['Company', 'Solutions', 'Industries', 'Resources', 'Global', 'Contact'])
        ->assertSee('Privacy Policy')
        ->assertSee('Terms &amp; Conditions', false)
        ->assertSee('Cookie Policy');

    foreach (app(CompanyProfile::class)->markets() as $market) {
        $response->assertSee($market['name']);
    }
});

it('outputs exactly one self-referencing canonical URL per page', function (string $routeName, array $parameters) {
    $url = route($routeName, $parameters);
    $html = $this->get($url)->assertOk()->getContent();

    expect(substr_count($html, 'rel="canonical"'))->toBe(1)
        ->and($html)->toContain('<link rel="canonical" href="'.$url.'">');
})->with([
    'home' => ['home', []],
    'about' => ['about', []],
    'contact' => ['contact', []],
    'case studies' => ['case-studies.index', []],
    'blog' => ['blog.index', []],
]);

it('outputs Organization and WebSite structured data', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)->toContain('"@type": "Organization"')
        ->and($html)->toContain('"@type": "WebSite"');
});

it('outputs BreadcrumbList structured data alongside visible breadcrumbs', function () {
    $html = $this->get(route('case-studies.index'))->assertOk()->getContent();

    expect($html)->toContain('"@type":"BreadcrumbList"')
        ->and($html)->toContain('aria-label="Breadcrumb"');
});

it('renders the reusable FAQ component with FAQPage schema', function () {
    $html = $this->blade('<x-public.faq :items="$items" />', [
        'items' => [['question' => 'How long does a project take?', 'answer' => 'Most projects take 8 to 12 weeks.']],
    ]);

    $html->assertSee('How long does a project take?')
        ->assertSee('Most projects take 8 to 12 weeks.');
});
