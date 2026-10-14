<?php

use App\Models\Capability;
use App\Models\CaseStudy;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function prioritySectionsContent(array $overrides = []): array
{
    return array_replace_recursive([
        'problems' => [
            'enabled' => true,
            'heading' => 'Business Problems We Solve',
            'items' => [['problem' => 'Data lives in spreadsheets.', 'solution' => 'One central platform.']],
        ],
        'benefits' => [
            'enabled' => true,
            'heading' => 'Business Benefits',
            'items' => [['title' => 'Lower Operating Costs', 'description' => 'Less manual work.']],
        ],
        'sub_services' => [
            'enabled' => true,
            'items' => [['title' => 'Customer & Employee Portals', 'description' => 'Self-service portals.']],
        ],
    ], $overrides);
}

function runPriorityServicesMigration(): void
{
    (require database_path('migrations/2026_10_14_133015_add_international_content_to_priority_services.php'))->up();
}

function runHomePositioningMigration(): void
{
    (require database_path('migrations/2026_10_14_133016_update_home_content_for_international_positioning.php'))->up();
}

it('renders the problems, benefits and related services sections on a service page', function () {
    $related = Service::factory()->create(['title' => 'Website Design & Development']);
    $service = Service::factory()->create([
        'content' => prioritySectionsContent([
            'related' => ['enabled' => true, 'heading' => 'Related Services', 'service_ids' => [$related->id]],
        ]),
    ]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSeeInOrder(['Business Problems We Solve', 'Customer &amp; Employee Portals', 'Business Benefits', 'Related Services'], false)
        ->assertSee('Data lives in spreadsheets.')
        ->assertSee('One central platform.')
        ->assertSee('Lower Operating Costs')
        ->assertSee(route('services.show', $related->slug));
});

it('hides the new sections when they are switched off', function () {
    $service = Service::factory()->create([
        'content' => prioritySectionsContent([
            'problems' => ['enabled' => false],
            'benefits' => ['enabled' => false],
            'related' => ['enabled' => false],
        ]),
    ]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertDontSee('Data lives in spreadsheets.')
        ->assertDontSee('Lower Operating Costs')
        ->assertDontSee('svc-related', false);
});

it('falls back to active services under the same capability when no related services are picked', function () {
    $capability = Capability::factory()->create();
    $service = Service::factory()->create(['capability_id' => $capability->id]);
    $sibling = Service::factory()->create(['capability_id' => $capability->id, 'title' => 'Sibling Service']);
    $inactive = Service::factory()->create(['capability_id' => $capability->id, 'title' => 'Hidden Sibling', 'is_active' => false]);
    $other = Service::factory()->create(['title' => 'Unrelated Service']);

    expect($service->relatedServices()->pluck('id')->all())->toBe([$sibling->id]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSee('Sibling Service')
        ->assertDontSee('Hidden Sibling')
        ->assertDontSee('Unrelated Service');
});

it('shows the picked case studies in the picked order, or the three most recent', function () {
    $first = CaseStudy::factory()->create(['title' => 'Picked First']);
    $second = CaseStudy::factory()->create(['title' => 'Picked Second']);
    CaseStudy::factory()->create(['title' => 'Not Picked']);

    $service = Service::factory()->create([
        'content' => ['case_studies' => ['enabled' => true, 'ids' => [$second->id, $first->id]]],
    ]);

    expect($service->selectedCaseStudies()->pluck('id')->all())->toBe([$second->id, $first->id]);

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSeeInOrder(['Picked Second', 'Picked First'])
        ->assertDontSee('Not Picked');

    $service->update(['content' => ['case_studies' => ['enabled' => true]]]);

    expect($service->fresh()->selectedCaseStudies())->toHaveCount(3);
});

it('saves the new sections and the homepage feature flag from the admin form', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $service = Service::factory()->create();
    $related = Service::factory()->create();
    $caseStudy = CaseStudy::factory()->create();

    $this->actingAs($admin)->get(route('admin.services.edit', $service))
        ->assertOk()
        ->assertSee('Problems &amp; Solutions', false)
        ->assertSee('Business Benefits')
        ->assertSee('Related Services')
        ->assertSee('Feature in homepage core services');

    $this->actingAs($admin)->put(route('admin.services.update', $service), [
        'title' => $service->title,
        'slug' => $service->slug,
        'short_description' => 'Short.',
        'is_active' => '1',
        'is_featured' => '1',
        'content' => [
            'problems' => [
                'enabled' => '1',
                'heading' => 'Problems We Solve',
                'items' => [['problem' => 'Slow website.', 'solution' => 'Faster pages.'], ['problem' => '', 'solution' => '']],
            ],
            'benefits' => [
                'enabled' => '0',
                'items' => [['title' => 'More enquiries', 'description' => 'Better conversion.']],
            ],
            'case_studies' => ['enabled' => '1', 'ids' => [(string) $caseStudy->id]],
            'related' => ['enabled' => '1', 'service_ids' => [(string) $related->id]],
        ],
    ])->assertRedirect(route('admin.services.edit', $service))->assertSessionHasNoErrors();

    $service->refresh();

    expect($service->is_featured)->toBeTrue()
        ->and($service->content['problems']['items'])->toBe([['problem' => 'Slow website.', 'solution' => 'Faster pages.']])
        ->and($service->content['problems']['enabled'])->toBeTrue()
        ->and($service->content['benefits']['enabled'])->toBeFalse()
        ->and($service->content['benefits']['items'][0]['title'])->toBe('More enquiries')
        ->and($service->content['case_studies']['ids'])->toBe([$caseStudy->id])
        ->and($service->content['related']['service_ids'])->toBe([$related->id]);
});

it('rejects related services and case studies that do not exist', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $service = Service::factory()->create();

    $this->actingAs($admin)->put(route('admin.services.update', $service), [
        'title' => $service->title,
        'slug' => $service->slug,
        'short_description' => 'Short.',
        'content' => [
            'case_studies' => ['ids' => [999]],
            'related' => ['service_ids' => [999]],
        ],
    ])->assertSessionHasErrors(['content.case_studies.ids.0', 'content.related.service_ids.0']);
});

it('shows featured services in the homepage core services section', function () {
    Service::factory()->featured()->create([
        'title' => 'Custom Software Development',
        'content' => prioritySectionsContent(),
    ]);
    Service::factory()->create(['title' => 'Unfeatured Service']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['tx-hero', 'id="core-services"', 'Custom Software Development', 'Four Pillars'], false)
        ->assertSee('Problems We Solve')
        ->assertSee('Data lives in spreadsheets.')
        ->assertSee('Lower Operating Costs')
        ->assertSee('Customer &amp; Employee Portals', false)
        ->assertSee(route('contact').'#contact-lead-form', false)
        ->assertDontSee('Unfeatured Service');
});

it('omits the core services section when no service is featured', function () {
    Service::factory()->create();

    $this->get(route('home'))->assertOk()->assertDontSee('id="core-services"', false);
});

it('adds the international sections to the priority services without overwriting admin edits', function () {
    $website = Service::factory()->create([
        'slug' => 'website-design-development',
        'seo_title' => 'Best Website Development Company in Navi Mumbai | Tectignis',
        'content' => [
            'hero' => ['heading' => 'Professional Website Design & Development Services in Navi Mumbai'],
            'sub_services' => ['items' => [['icon' => null, 'title' => 'Landing Page Development', 'description' => 'Existing.']]],
        ],
    ]);
    $software = Service::factory()->create([
        'slug' => 'custom-software-development',
        'seo_title' => 'An Admin Written Title',
        'content' => [
            'hero' => ['heading' => 'An Admin Written Heading'],
            'features_strip' => ['items' => [['icon' => null, 'label' => 'Lifetime Technical Support']]],
            'problems' => ['enabled' => true, 'items' => [['problem' => 'Admin problem', 'solution' => 'Admin fix']]],
        ],
    ]);
    $ecommerce = Service::factory()->create(['slug' => 'ecommerce-development']);

    runPriorityServicesMigration();

    $website->refresh();
    $software->refresh();

    expect($website->is_featured)->toBeTrue()
        ->and($website->seo_title)->toBe('Website Design & Development Company | Tectignis IT Solutions')
        ->and($website->content['hero']['heading'])->toBe('Professional Website Design & Development for Growing Businesses')
        ->and($website->content['problems']['items'])->toHaveCount(6)
        ->and($website->content['benefits']['items'])->toHaveCount(6)
        ->and($website->content['related']['service_ids'])->toBe([$software->id, $ecommerce->id])
        ->and(collect($website->content['sub_services']['items'])->where('title', 'Landing Page Development'))->toHaveCount(1)
        ->and($website->content['sub_services']['items'])->toHaveCount(3);

    expect($software->is_featured)->toBeTrue()
        ->and($software->seo_title)->toBe('An Admin Written Title')
        ->and($software->content['hero']['heading'])->toBe('An Admin Written Heading')
        ->and($software->content['features_strip']['items'][0]['label'])->toBe('Long-Term Technical Support')
        ->and($software->content['problems']['items'])->toBe([['problem' => 'Admin problem', 'solution' => 'Admin fix']])
        ->and($software->content['related']['service_ids'])->toBe([$website->id]);
});

it('repositions the homepage copy, aligns the careers figures and corrects the legal page phone', function () {
    Setting::updateOrCreate(['key' => 'hero_heading_line1'], ['value' => 'Build, Modernize & Secure', 'group' => 'home']);
    Setting::updateOrCreate(['key' => 'hero_btn_secondary'], ['value' => 'An Admin Label', 'group' => 'home']);
    Setting::updateOrCreate(['key' => 'careers_stats'], ['group' => 'careers', 'value' => json_encode([
        ['icon' => 'fas fa-users', 'value' => '205+', 'label' => 'Projects Delivered'],
        ['icon' => 'fas fa-smile', 'value' => '100%', 'label' => 'Learning Culture'],
    ])]);
    Stat::query()->delete();
    Setting::updateOrCreate(['key' => 'site_phone'], ['value' => '+91 9987805688', 'group' => 'general']);
    Setting::updateOrCreate(['key' => 'legal_privacy-policy'], ['value' => '<li>Phone: +91 9987705688</li>', 'group' => 'legal']);
    Stat::factory()->create(['key' => 'projects', 'value' => '150+', 'label' => 'Projects Delivered', 'is_active' => true]);

    runHomePositioningMigration();

    expect(Setting::get('hero_heading_line1'))->toBe('Custom Software & Websites')
        ->and(Setting::get('hero_btn_secondary'))->toBe('An Admin Label')
        ->and(Setting::get('core_pretitle'))->toBe('Core Services')
        ->and(array_column(Setting::json('careers_stats'), 'value'))->toBe(['150+', '100%'])
        ->and(Setting::get('legal_privacy-policy'))->toBe('<li>Phone: +91 9987805688</li>');
});
