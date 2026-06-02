<?php

use App\Models\BlogPost;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sitemap returns 200 with xml content type', function () {
    $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml');
});

it('sitemap contains service urls', function () {
    $service = Service::factory()->create(['is_active' => true, 'slug' => 'web-design-test']);

    $response = $this->get(route('sitemap'));
    $response->assertOk();
    $response->assertSee(route('services.show', $service->slug), false);
});

it('sitemap contains blog post urls', function () {
    $post = BlogPost::factory()->create([
        'is_published' => true,
        'published_at' => now(),
        'slug' => 'my-test-post',
    ]);

    $response = $this->get(route('sitemap'));
    $response->assertOk();
    $response->assertSee(route('blog.show', $post->slug), false);
});

it('sitemap contains static page urls', function () {
    $response = $this->get(route('sitemap'));
    $response->assertOk();
    $response->assertSee(route('home'), false);
    $response->assertSee(route('capabilities.index'), false);
    $response->assertSee(route('blog.index'), false);
});
