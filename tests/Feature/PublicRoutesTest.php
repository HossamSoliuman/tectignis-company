<?php

use App\Models\BlogPost;
use App\Models\Service;
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
    $service = Service::factory()->create(['is_active' => true, 'slug' => 'test-service']);

    $this->get(route('capabilities.show', $service->slug))->assertOk();
});

it('capabilities show returns 404 for unknown slug', function () {
    $this->get(route('capabilities.show', 'nonexistent-service'))->assertNotFound();
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
