<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('gives the mobile menu button and the drawer close button their controls', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('id="admin-sidebar"', false)
        ->assertSee('aria-controls="admin-sidebar" aria-expanded="false"', false)
        ->assertSee('data-sidebar-close', false)
        ->assertSee('aria-label="Close menu"', false);
});

it('marks only the current page in the sidebar', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $html = $this->actingAs($admin)->get(route('admin.stats.index'))->assertOk()->getContent();
    $currentLink = fn (string $route): string => '~<a href="'.preg_quote(route($route), '~').'"\s+aria-current="page"~';

    expect($html)
        ->toMatch($currentLink('admin.stats.index'))
        ->not->toMatch($currentLink('admin.dashboard'))
        ->not->toMatch($currentLink('admin.testimonials.index'));
});
