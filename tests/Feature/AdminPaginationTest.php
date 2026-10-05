<?php

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Stat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
});

it('paginates CMS tables at 20 rows by default', function () {
    Stat::factory()->count(25)->create();

    $response = $this->get(route('admin.stats.index'))
        ->assertOk()
        ->assertSee('Showing')
        ->assertSee('1–20');

    expect($response->viewData('stats')->count())->toBe(20)
        ->and($response->viewData('stats')->total())->toBe(Stat::count());
});

it('honours the 10/20/50/100 page-size options', function (int $size) {
    Stat::factory()->count(30)->create();

    $response = $this->get(route('admin.stats.index', ['per_page' => $size]))->assertOk();

    expect($response->viewData('stats')->perPage())->toBe($size);
})->with([10, 20, 50, 100]);

it('ignores unsupported page sizes', function () {
    $response = $this->get(route('admin.stats.index', ['per_page' => 7]))->assertOk();

    expect($response->viewData('stats')->perPage())->toBe(20);
});

it('searches server-side and keeps the search in page links', function () {
    BlogPost::factory()->count(12)->create(['title' => 'Cloud migration notes']);
    BlogPost::factory()->create(['title' => 'Unrelated topic']);

    $response = $this->get(route('admin.blog.index', ['q' => 'Cloud', 'per_page' => 10]))
        ->assertOk()
        ->assertDontSee('Unrelated topic');

    expect($response->viewData('posts')->total())->toBe(12);
    $response->assertSee('q=Cloud', false)->assertSee('page=2', false);
});

it('shows an empty state when nothing matches', function () {
    Faq::factory()->create(['question' => 'How long does it take?']);

    $this->get(route('admin.faqs.index', ['q' => 'nothing-like-this']))
        ->assertOk()
        ->assertSee('No records');
});

it('renders every paginated CMS listing', function (string $route) {
    $this->get(route($route))->assertOk()->assertSee('Rows');
})->with([
    'admin.blog.index', 'admin.brands.index', 'admin.capabilities.index', 'admin.case-study-categories.index',
    'admin.case-studies.index', 'admin.downloads.index', 'admin.faq-categories.index', 'admin.faqs.index',
    'admin.global-advantages.index', 'admin.industries.index', 'admin.insights.index', 'admin.job-openings.index',
    'admin.office-locations.index', 'admin.pages.index', 'admin.process-steps.index', 'admin.redirects.index',
    'admin.services.index', 'admin.solutions.index', 'admin.stats.index', 'admin.tech-stacks.index',
    'admin.testimonials.index', 'admin.why-choose-features.index', 'admin.users.index',
]);
