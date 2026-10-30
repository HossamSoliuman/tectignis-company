<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['services.country_detection.header' => 'CF-IPCountry']);
});

it('sets Consent Mode defaults before any tag loads', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain("gtag('consent', 'default', Object.assign(states('denied')")
        ->toContain('"DE"')
        ->toContain('"GB"')
        ->toContain("gtag('consent', 'default', states('granted'))");

    expect(strpos($html, "gtag('consent', 'default'"))->toBeLessThan(strpos($html, 'id="tx-consent"'));
});

it('asks visitors in the EEA, UK and Switzerland to opt in', function (string $country) {
    $this->withHeader('CF-IPCountry', $country)
        ->get(route('home'))
        ->assertSee('window.txConsentRequired = true', false);
})->with(['DE', 'FR', 'GB', 'CH']);

it('does not ask visitors elsewhere', function (string $country) {
    $this->withHeader('CF-IPCountry', $country)
        ->get(route('home'))
        ->assertSee('window.txConsentRequired = false', false);
})->with(['IN', 'US', 'AE']);

it('renders the banner and a way to reopen it from the footer', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('id="tx-consent"', false)
        ->assertSee('data-consent-choice="denied"', false)
        ->assertSee('data-consent-choice="granted"', false)
        ->assertSee('data-consent-open', false)
        ->assertSee('Cookie Settings');
});
