<?php

use App\Models\Capability;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adds baseline security headers to public responses', function () {
    $response = $this->get(route('home'));

    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

    expect($response->headers->get('Content-Security-Policy-Report-Only'))
        ->toContain("default-src 'self'");
});

it('renders contact details from admin settings', function () {
    Setting::create(['key' => 'site_phone', 'value' => '+91 1112223334', 'group' => 'general']);
    Setting::create(['key' => 'site_email', 'value' => 'hello@example.test', 'group' => 'general']);
    Setting::create(['key' => 'business_hours', 'value' => 'Custom Hours Window', 'group' => 'general']);

    $response = $this->get(route('contact'));

    $response->assertSee('+91 1112223334');
    $response->assertSee('tel:+911112223334', false);
    $response->assertSee('hello@example.test');
    $response->assertSee('Custom Hours Window');
});

it('outputs FAQ schema with the new questions on the contact page', function () {
    $this->get(route('contact'))
        ->assertSee('"@type":"FAQPage"', false)
        ->assertSee('How do I get started with Tectignis?', false);
});

it('renders editable core values copy from settings', function () {
    Setting::create(['key' => 'about_values_subtitle', 'value' => 'My Bespoke Values Subtitle', 'group' => 'about']);

    $this->get(route('about'))
        ->assertSee('Customer Success')
        ->assertSee('My Bespoke Values Subtitle');
});

it('keeps a capability live on the listing while hiding it from the header menu', function () {
    Capability::factory()->create(['is_active' => true, 'show_in_menu' => true, 'title' => 'Visible Capability XYZ', 'slug' => 'visible-cap-xyz']);
    Capability::factory()->create(['is_active' => true, 'show_in_menu' => false, 'title' => 'Hidden Capability QWE', 'slug' => 'hidden-cap-qwe']);

    // The contact page renders the header mega-menu but does not list capabilities itself.
    $this->get(route('contact'))
        ->assertSee('Visible Capability XYZ')
        ->assertDontSee('Hidden Capability QWE');

    // Both remain publicly reachable on the capabilities listing.
    $this->get(route('capabilities.index'))
        ->assertSee('Visible Capability XYZ')
        ->assertSee('Hidden Capability QWE');
});
