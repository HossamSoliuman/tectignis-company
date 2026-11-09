<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sends retired urls to their current page', function (string $from, string $to) {
    $this->get($from)
        ->assertStatus(301)
        ->assertRedirect(url($to));
})->with([
    'php extension' => ['/contact-us.php', '/contact'],
    'old service page' => ['/custom-software-development-navi-mumbai', '/services/custom-software-development'],
    'old home' => ['/index.html', '/'],
    'removed listing' => ['/services', '/capabilities'],
]);
