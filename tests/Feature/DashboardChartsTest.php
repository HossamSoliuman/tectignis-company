<?php

use App\Models\Lead;
use App\Models\PageVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('records a page visit for a public GET request', function () {
    $this->get(route('home'))->assertOk();

    expect(PageVisit::where('path', '/')->count())->toBe(1);
});

it('does not track admin or login pages', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->get(route('login'))->assertOk();
    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();

    expect(PageVisit::whereIn('path', ['/login', '/admin'])->count())->toBe(0);
});

it('renders the dashboard with visit and lead charts', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    PageVisit::create(['path' => '/about', 'created_at' => now()->subDays(2), 'updated_at' => now()]);
    PageVisit::create(['path' => '/about', 'created_at' => now()->subDay(), 'updated_at' => now()]);
    Lead::factory()->create(['created_at' => now()->subDay()]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Website Analytics')
        ->assertSee('Leads Overview')
        ->assertSee('Recent Leads')
        ->assertSee('Top Pages')
        ->assertSee('/about')
        ->assertDontSee('Upload an image');
});
