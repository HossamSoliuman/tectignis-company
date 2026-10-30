<?php

use App\Models\CaseStudy;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function careersAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

it('admin can view the careers content editor', function () {
    $this->actingAs(careersAdmin())
        ->get(route('admin.careers-content.edit'))
        ->assertOk()
        ->assertSee('Careers Page')
        ->assertSee('Stats strip');
});

it('admin can save careers stats and benefits', function () {
    $this->actingAs(careersAdmin())
        ->put(route('admin.careers-content.update'), [
            'stats' => [
                ['icon' => 'fas fa-briefcase', 'value' => '50+', 'label' => 'Projects Delivered'],
                ['icon' => '', 'value' => '', 'label' => ''], // blank row is dropped
            ],
            'benefits' => [
                ['icon' => 'fas fa-rocket', 'title' => 'Career Growth', 'text' => 'Advance your career.'],
            ],
        ])
        ->assertRedirect();

    expect(Setting::json('careers_stats'))->toHaveCount(1)
        ->and(Setting::json('careers_stats')[0]['label'])->toBe('Projects Delivered')
        ->and(Setting::json('careers_benefits'))->toHaveCount(1)
        ->and(Setting::json('careers_benefits')[0]['title'])->toBe('Career Growth');
});

it('renders admin-managed careers content on the public page', function () {
    Setting::set('careers_stats', json_encode([
        ['icon' => 'fas fa-briefcase', 'value' => '50+', 'label' => 'Projects Delivered'],
    ]), 'careers');
    Setting::set('careers_benefits', json_encode([
        ['icon' => 'fas fa-rocket', 'title' => 'Career Growth', 'text' => 'Advance your career.'],
    ]), 'careers');

    $this->get(route('careers'))
        ->assertOk()
        ->assertSee('Projects Delivered')
        ->assertSee('Career Growth');
});

it('shows a case study detail page and 404s on unknown or inactive slugs', function () {
    $active = CaseStudy::factory()->create(['slug' => 'live-study', 'title' => 'Live Study']);
    $inactive = CaseStudy::factory()->draft()->create(['slug' => 'hidden-study']);

    $this->get(route('case-studies.show', $active->slug))
        ->assertOk()
        ->assertSee('Live Study');

    $this->get(route('case-studies.show', $inactive->slug))->assertNotFound();
    $this->get(route('case-studies.show', 'does-not-exist'))->assertNotFound();
});

it('reads linked careers stats from the approved company figures', function () {
    Stat::factory()->create(['key' => 'projects', 'label' => 'Projects Delivered', 'value' => '120+', 'is_active' => true]);
    Stat::factory()->create(['key' => 'clients', 'label' => 'Happy Clients', 'value' => '40+', 'is_active' => false]);
    Setting::set('careers_stats', json_encode([
        ['icon' => 'fas fa-briefcase', 'stat' => 'projects', 'label' => 'Projects Shipped'],
        ['icon' => 'fas fa-handshake', 'stat' => 'clients'],
        ['icon' => 'fas fa-book-open', 'value' => 'Continuous', 'label' => 'Learning'],
    ]), 'careers');

    $this->get(route('careers'))
        ->assertOk()
        ->assertSee('120+')
        ->assertSee('Projects Shipped')
        ->assertDontSee('40+')
        ->assertSee('Continuous');
});

it('does not fall back to unapproved figures on the careers page', function () {
    $this->get(route('careers'))
        ->assertOk()
        ->assertDontSee('Team Members')
        ->assertDontSee('Hiring Experts')
        ->assertDontSee('Employee Satisfaction');
});

it('saves a careers stat linked to a company figure without a typed value', function () {
    Stat::factory()->create(['key' => 'projects', 'label' => 'Projects Delivered', 'value' => '120+']);

    $this->actingAs(careersAdmin())
        ->put(route('admin.careers-content.update'), [
            'stats' => [
                ['icon' => 'fas fa-briefcase', 'stat' => 'projects', 'value' => '999+', 'label' => ''],
                ['icon' => 'fas fa-book-open', 'stat' => '', 'value' => 'Continuous', 'label' => 'Learning'],
            ],
        ])
        ->assertRedirect();

    expect(Setting::json('careers_stats'))->toBe([
        ['icon' => 'fas fa-briefcase', 'stat' => 'projects'],
        ['icon' => 'fas fa-book-open', 'value' => 'Continuous', 'label' => 'Learning'],
    ]);

    $this->get(route('careers'))->assertSee('120+')->assertDontSee('999+');
});

it('hides the countries figure on the about page when it is switched off', function () {
    Stat::query()->updateOrCreate(['key' => 'countries'], ['label' => 'Countries Served', 'value' => '31+', 'is_active' => false]);

    $this->get(route('about'))->assertOk()->assertDontSee('31+');
});
