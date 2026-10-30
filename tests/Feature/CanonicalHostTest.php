<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://tectignis.in']);
});

it('redirects the www host to the bare domain, keeping path and query', function () {
    $this->get('https://www.tectignis.in/contact?utm_source=google&gclid=abc')
        ->assertStatus(301)
        ->assertRedirect('https://tectignis.in/contact?utm_source=google&gclid=abc');
});

it('redirects form posts on the www host too', function () {
    $this->post('https://www.tectignis.in/contact')->assertStatus(301);
});

it('serves the canonical host normally', function () {
    $this->get('https://tectignis.in/robots.txt')->assertOk();
});

it('leaves unrelated hosts alone', function () {
    $this->get('https://staging.tectignis.in/robots.txt')->assertOk();
});
