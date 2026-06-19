<?php

use App\Models\BlogPost;
use App\Models\Capability;
use App\Models\Industry;
use App\Models\Page;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('home page returns 200', function () {
    $this->get(route('home'))->assertOk();
});

it('about page returns 200', function () {
    $this->get(route('about'))->assertOk();
});

it('capabilities index returns 200', function () {
    $this->get(route('capabilities.index'))->assertOk();
});

it('capabilities show returns 200 for valid slug', function () {
    $capability = Capability::factory()->create(['is_active' => true, 'slug' => 'test-capability']);

    $this->get(route('capabilities.show', $capability->slug))->assertOk();
});

it('capabilities show returns 404 for unknown slug', function () {
    $this->get(route('capabilities.show', 'nonexistent-capability'))->assertNotFound();
});

it('services index returns 200', function () {
    $this->get(route('services.index'))->assertOk();
});

it('services show returns 200 for valid slug', function () {
    $service = Service::factory()->create(['is_active' => true, 'slug' => 'test-service']);

    $this->get(route('services.show', $service->slug))->assertOk();
});

it('solutions index returns 200', function () {
    $this->get(route('solutions.index'))->assertOk();
});

it('solutions show returns 200 for valid slug', function () {
    $solution = Solution::factory()->create(['is_active' => true, 'slug' => 'test-solution']);

    $this->get(route('solutions.show', $solution->slug))->assertOk();
});

it('industries index returns 200', function () {
    $this->get(route('industries.index'))->assertOk();
});

it('industries show returns 200 for valid slug', function () {
    $industry = Industry::factory()->create(['is_active' => true, 'slug' => 'test-industry']);

    $this->get(route('industries.show', $industry->slug))->assertOk();
});

it('renders a custom page with a raw HTML body', function () {
    $page = Page::factory()->create([
        'is_active' => true,
        'slug' => 'my-custom-page',
        'body' => '<section class="custom-marker">Hello from the CMS</section>',
    ]);

    $this->get(route('pages.show', $page->slug))
        ->assertOk()
        ->assertSee('custom-marker', false)
        ->assertSee('Hello from the CMS');
});

it('returns 404 for an inactive custom page', function () {
    $page = Page::factory()->create(['is_active' => false, 'slug' => 'hidden-page']);

    $this->get(route('pages.show', $page->slug))->assertNotFound();
});

it('renders a solution detail using its custom HTML body when present', function () {
    $solution = Solution::factory()->create([
        'is_active' => true,
        'slug' => 'body-solution',
        'body' => '<div class="solution-body-marker">Custom solution layout</div>',
    ]);

    $this->get(route('solutions.show', $solution->slug))
        ->assertOk()
        ->assertSee('solution-body-marker', false);
});

it('renders the rich solution landing template with the dark hero theme and sections', function () {
    $solution = Solution::factory()->create([
        'is_active' => true,
        'slug' => 'rich-solution',
        'body' => null,
        'content' => [
            'hero' => ['theme' => 'dark', 'heading' => 'Intelligent Solutions'],
            'modules' => ['enabled' => true, 'heading' => 'Our Test Modules', 'cards' => [['title' => 'First Module', 'description' => 'Does things.']]],
            'process' => ['enabled' => true, 'heading' => 'Our Test Process', 'steps' => [['title' => 'Discover', 'description' => 'Step one.']]],
            'cta_band' => ['enabled' => true, 'heading' => 'Ready to start?'],
        ],
    ]);

    $this->get(route('solutions.show', $solution->slug))
        ->assertOk()
        ->assertSee('ind-hero--dark', false)
        ->assertSee('Our Test Modules')
        ->assertSee('First Module')
        ->assertSee('Our Test Process')
        ->assertDontSee('solution-body-marker', false);
});

it('uses the light hero theme by default for solutions', function () {
    $solution = Solution::factory()->create([
        'is_active' => true,
        'slug' => 'light-solution',
        'body' => null,
        'content' => ['hero' => ['heading' => 'Light Hero Solution']],
    ]);

    $this->get(route('solutions.show', $solution->slug))
        ->assertOk()
        ->assertDontSee('ind-hero--dark', false);
});

it('careers page returns 200', function () {
    $this->get(route('careers'))->assertOk();
});

it('case studies index returns 200', function () {
    $this->get(route('case-studies.index'))->assertOk();
});

it('blog index returns 200', function () {
    $this->get(route('blog.index'))->assertOk();
});

it('blog show returns 200 for published post', function () {
    $post = BlogPost::factory()->create([
        'is_published' => true,
        'published_at' => now(),
        'slug' => 'test-post',
    ]);

    $this->get(route('blog.show', $post->slug))->assertOk();
});

it('contact page returns 200', function () {
    $this->get(route('contact'))->assertOk();
});

it('privacy policy page returns 200', function () {
    $this->get(route('legal.show', 'privacy-policy'))->assertOk();
});

it('terms page returns 200', function () {
    $this->get(route('legal.show', 'terms-and-conditions'))->assertOk();
});

it('sitemap returns 200', function () {
    $this->get(route('sitemap'))->assertOk();
});

it('robots txt returns 200', function () {
    $this->get(route('robots'))->assertOk();
});
