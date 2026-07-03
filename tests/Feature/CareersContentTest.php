<?php

use App\Models\CaseStudy;
use App\Models\Setting;
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
    $active = CaseStudy::factory()->create(['is_active' => true, 'slug' => 'live-study', 'title' => 'Live Study']);
    $inactive = CaseStudy::factory()->create(['is_active' => false, 'slug' => 'hidden-study']);

    $this->get(route('case-studies.show', $active->slug))
        ->assertOk()
        ->assertSee('Live Study');

    $this->get(route('case-studies.show', $inactive->slug))->assertNotFound();
    $this->get(route('case-studies.show', 'does-not-exist'))->assertNotFound();
});
