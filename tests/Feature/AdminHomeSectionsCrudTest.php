<?php

use App\Models\CaseStudy;
use App\Models\GlobalAdvantage;
use App\Models\OfficeLocation;
use App\Models\ProcessStep;
use App\Models\User;
use App\Models\WhyChooseFeature;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function homeAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

it('admin can view all home-section indexes', function () {
    $admin = homeAdmin();

    foreach ([
        'admin.why-choose-features.index',
        'admin.office-locations.index',
        'admin.global-advantages.index',
        'admin.process-steps.index',
    ] as $route) {
        $this->actingAs($admin)->get(route($route))->assertOk();
    }
});

it('admin can create a why-choose feature', function () {
    $this->actingAs(homeAdmin())
        ->post(route('admin.why-choose-features.store'), [
            'icon' => 'fas fa-globe',
            'title' => 'Global Delivery',
            'text' => 'Round-the-clock progress.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.why-choose-features.index'));

    $this->assertDatabaseHas('why_choose_features', ['title' => 'Global Delivery']);
});

it('admin can create an office location', function () {
    $this->actingAs(homeAdmin())
        ->post(route('admin.office-locations.store'), [
            'region' => 'global',
            'city' => 'Singapore',
            'type' => 'Southeast Asia Partner',
            'is_active' => '1',
        ])->assertRedirect(route('admin.office-locations.index'));

    $this->assertDatabaseHas('office_locations', ['city' => 'Singapore', 'region' => 'global']);
});

it('admin can create a global advantage', function () {
    $this->actingAs(homeAdmin())
        ->post(route('admin.global-advantages.store'), [
            'icon' => '<path d="M12 6v6h4.5"/>',
            'title' => 'Global Reach',
            'description' => 'Presence in 25+ countries',
            'tone' => 'rose',
            'is_active' => '1',
        ])->assertRedirect(route('admin.global-advantages.index'));

    $this->assertDatabaseHas('global_advantages', ['title' => 'Global Reach']);
});

it('admin can create a process step', function () {
    $this->actingAs(homeAdmin())
        ->post(route('admin.process-steps.store'), [
            'icon' => '<path d="M12 6v6h4.5"/>',
            'title' => 'Understand',
            'description' => 'We understand your business challenges',
            'is_active' => '1',
        ])->assertRedirect(route('admin.process-steps.index'));

    $this->assertDatabaseHas('process_steps', ['title' => 'Understand']);
});

it('admin can update and delete a why-choose feature', function () {
    $feature = WhyChooseFeature::factory()->create();

    $this->actingAs(homeAdmin())
        ->put(route('admin.why-choose-features.update', $feature), [
            'icon' => $feature->icon,
            'title' => 'Updated Title',
            'text' => 'Updated text.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.why-choose-features.index'));

    $this->assertDatabaseHas('why_choose_features', ['id' => $feature->id, 'title' => 'Updated Title']);

    $this->actingAs(homeAdmin())
        ->delete(route('admin.why-choose-features.destroy', $feature))
        ->assertRedirect(route('admin.why-choose-features.index'));

    $this->assertDatabaseMissing('why_choose_features', ['id' => $feature->id]);
});

it('stores case-study features from a newline-separated textarea', function () {
    $this->actingAs(homeAdmin())
        ->post(route('admin.case-studies.store'), [
            'title' => 'ERP for Manufacturing',
            'slug' => 'erp-manufacturing',
            'category' => 'ERP SOLUTION',
            'theme' => 'blue',
            'short_description' => 'Streamlined operations.',
            'features' => "40% increase in efficiency\nReal-time visibility\n",
            'is_active' => '1',
        ])->assertRedirect(route('admin.case-studies.index'));

    $caseStudy = CaseStudy::firstWhere('slug', 'erp-manufacturing');

    expect($caseStudy->features)->toBe(['40% increase in efficiency', 'Real-time visibility']);
    expect($caseStudy->theme)->toBe('blue');
});

it('home page renders home-section content from the database', function () {
    WhyChooseFeature::factory()->create(['title' => 'Dedicated Delivery']);
    OfficeLocation::factory()->create(['region' => 'india', 'city' => 'Navi Mumbai']);
    GlobalAdvantage::factory()->create(['title' => 'Agile Delivery']);
    ProcessStep::factory()->create(['title' => 'Strategize']);
    CaseStudy::factory()->create([
        'is_active' => true,
        'title' => 'Cloud Migration Project',
        'features' => ['99.9% uptime'],
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Dedicated Delivery')
        ->assertSee('Navi Mumbai')
        ->assertSee('Agile Delivery')
        ->assertSee('Strategize')
        ->assertSee('Cloud Migration Project')
        ->assertSee('99.9% uptime');
});
