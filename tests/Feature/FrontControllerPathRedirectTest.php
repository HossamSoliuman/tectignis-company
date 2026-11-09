<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://tectignis.in']);
});

it('redirects front controller paths to the clean url', function (string $from, string $to) {
    $this->get('https://tectignis.in'.$from)
        ->assertStatus(301)
        ->assertRedirect('https://tectignis.in'.$to);
})->with([
    'page' => ['/public/index.php/industries', '/industries'],
    'nested page' => ['/public/index.php/blog/some-post', '/blog/some-post'],
    'public prefix only' => ['/public/industries', '/industries'],
    'query string' => ['/public/index.php/contact?utm_source=google', '/contact?utm_source=google'],
    'home' => ['/public/index.php', '/'],
    'home with slash' => ['/public/', '/'],
    'home with query' => ['/public/index.php?a=1', '/?a=1'],
]);

it('does not redirect form posts', function () {
    $response = $this->post('https://tectignis.in/public/index.php/contact');

    expect($response->getStatusCode())->not->toBe(301);
});

it('leaves clean urls and lookalike paths alone', function () {
    $this->get('https://tectignis.in/robots.txt')->assertOk();
    $this->get('https://tectignis.in/publications')->assertNotFound();
});
