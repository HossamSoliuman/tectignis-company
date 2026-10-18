<?php

use App\Models\LandingPage;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

describe('tag loading', function () {
    it('loads GA4 through the Tag Manager container only, when one is set', function () {
        Setting::set('site_gtm_id', 'GTM-ABC1234', 'integrations');
        Setting::set('site_ga_id', 'G-XYZ9876', 'integrations');

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('googletagmanager.com/gtm.js', false)
            ->assertSee('GTM-ABC1234', false)
            ->assertDontSee('googletagmanager.com/gtag/js', false)
            ->assertDontSee('G-XYZ9876', false);
    });

    it('loads GA4 on its own when no Tag Manager container is set', function () {
        Setting::set('site_gtm_id', null, 'integrations');
        Setting::set('site_ga_id', 'G-XYZ9876', 'integrations');

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('googletagmanager.com/gtag/js?id=G-XYZ9876', false)
            ->assertDontSee('googletagmanager.com/gtm.js', false);
    });

    it('loads neither when no tracking ID is set', function () {
        Setting::set('site_gtm_id', null, 'integrations');
        Setting::set('site_ga_id', null, 'integrations');

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('window.txTrack', false)
            ->assertDontSee('googletagmanager.com', false);
    });
});

describe('generate_lead', function () {
    it('reports a stored enquiry with what it is about and nothing about who sent it', function () {
        $response = $this->followingRedirects()
            ->post(route('contact.submit'), validEnquiry())
            ->assertOk()
            ->assertSee("window.txTrack('generate_lead', lead)", false);

        $conversion = $response->viewData('conversion');

        expect($conversion)
            ->toHaveKeys(['transaction_id', 'service', 'lead_source', 'landing_page', 'page_language'])
            ->toHaveCount(5)
            ->toMatchArray([
                'service' => 'Cloud Migration',
                'lead_source' => 'contact',
                'landing_page' => null,
                'page_language' => 'en',
            ])
            ->and(Str::isUuid($conversion['transaction_id']))->toBeTrue()
            ->and(json_encode($conversion))
            ->not->toContain('Jane')
            ->not->toContain('jane@acme.example')
            ->not->toContain('Acme')
            ->not->toContain('501234567');
    });

    it('credits the landing page and consultation intent the enquiry came from', function () {
        $page = LandingPage::factory()->create(['slug' => 'web-build-offer', 'enquiry_service' => 'Website Development']);

        $response = $this->followingRedirects()
            ->post(route('contact.submit'), validEnquiry([
                'service' => 'Website Development',
                'source' => 'consultation',
                'landing_page' => $page->slug,
            ]))
            ->assertOk();

        expect($response->viewData('conversion'))->toMatchArray([
            'service' => 'Website Development',
            'lead_source' => 'consultation',
            'landing_page' => 'web-build-offer',
        ]);
    });

    it('reports the language the enquiry was submitted in', function () {
        enableLanguages('ar');

        $response = $this->followingRedirects()
            ->post('/ar/contact', validEnquiry())
            ->assertOk();

        expect($response->viewData('conversion')['page_language'])->toBe('ar');
    });

    it('gives every enquiry its own transaction ID so Google Ads can drop repeats', function () {
        $first = $this->followingRedirects()->post(route('contact.submit'), validEnquiry())->viewData('conversion');
        $second = $this->followingRedirects()->post(route('contact.submit'), validEnquiry())->viewData('conversion');

        expect($first['transaction_id'])->not->toBe($second['transaction_id']);
    });

    it('never lets the browser keep the thank-you page', function () {
        $this->followingRedirects()
            ->post(route('contact.submit'), validEnquiry())
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private');
    });

    it('fires nothing without a submission', function () {
        $this->get(route('contact.thank-you'))
            ->assertRedirect(route('contact'));
    });
});

describe('contact clicks', function () {
    it('tracks consultation, call, email and WhatsApp clicks', function () {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee("'consultation_click'", false)
            ->assertSee("'phone_click'", false)
            ->assertSee("'email_click'", false)
            ->assertSee("'whatsapp_click'", false);
    });

    it('tags the landing page consultation CTA as a consultation click', function () {
        $page = LandingPage::factory()->create();

        $this->get(route('landing-pages.show', $page->slug))
            ->assertOk()
            ->assertSee('data-lp-cta="secondary" data-track="consultation_click"', false);
    });
});
