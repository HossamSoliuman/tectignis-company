<?php

use App\Models\Download;
use App\Models\Lead;
use App\Models\Setting;
use App\Services\RecaptchaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

/**
 * Configure reCAPTCHA as an administrator would.
 */
function enableRecaptcha(string $siteKey = 'site-key-123', string $secret = 'secret-key-456', bool $enabled = true): void
{
    Setting::set('recaptcha_site_key', $siteKey, 'captcha');
    Setting::set('recaptcha_secret_key', Crypt::encryptString($secret), 'captcha');
    Setting::set('recaptcha_enabled', $enabled ? '1' : '0', 'captcha');
}

it('accepts a submission with a valid token', function () {
    enableRecaptcha();
    Http::fake([RecaptchaService::VERIFY_URL => Http::response(['success' => true])]);

    $this->post(route('contact.submit'), validEnquiry(['g-recaptcha-response' => 'good-token']))
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::count())->toBe(1);

    Http::assertSent(fn ($request): bool => $request['secret'] === 'secret-key-456' && $request['response'] === 'good-token');
});

it('rejects an invalid token without creating a lead', function () {
    enableRecaptcha();
    Http::fake([RecaptchaService::VERIFY_URL => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']])]);

    $this->post(route('contact.submit'), validEnquiry(['g-recaptcha-response' => 'bad-token']))
        ->assertSessionHasErrors('g-recaptcha-response');

    expect(Lead::count())->toBe(0);
});

it('rejects a submission with no token', function () {
    enableRecaptcha();
    Http::fake();

    $this->post(route('contact.submit'), validEnquiry())
        ->assertSessionHasErrors('g-recaptcha-response');

    expect(Lead::count())->toBe(0);
    Http::assertNothingSent();
});

it('rejects the submission when Google cannot be reached', function () {
    enableRecaptcha();
    Http::fake(fn () => throw new ConnectionException('timeout'));

    $this->post(route('contact.submit'), validEnquiry(['g-recaptcha-response' => 'any']))
        ->assertSessionHasErrors('g-recaptcha-response');

    expect(Lead::count())->toBe(0);
});

it('skips verification when an administrator disables CAPTCHA', function () {
    enableRecaptcha(enabled: false);
    Http::fake();

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect(route('contact.thank-you'));

    expect(Lead::count())->toBe(1);
    Http::assertNothingSent();
});

it('uses new keys immediately without a deployment', function () {
    enableRecaptcha('old-site', 'old-secret');
    enableRecaptcha('new-site', 'new-secret');
    Http::fake([RecaptchaService::VERIFY_URL => Http::response(['success' => true])]);

    $this->get(route('contact'))->assertSee('data-sitekey="new-site"', false);

    $this->post(route('contact.submit'), validEnquiry(['g-recaptcha-response' => 'token']))->assertRedirect();

    Http::assertSent(fn ($request): bool => $request['secret'] === 'new-secret');
});

it('renders the v2 checkbox with the site key only', function () {
    enableRecaptcha();

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('class="g-recaptcha"', false)
        ->assertSee('data-sitekey="site-key-123"', false)
        ->assertSee('https://www.google.com/recaptcha/api.js', false)
        ->assertDontSee('secret-key-456')
        ->assertDontSee('?render=', false);
});

it('renders no widget while CAPTCHA is off', function () {
    $this->get(route('contact'))->assertOk()->assertDontSee('g-recaptcha', false);
});

it('protects the careers, newsletter and download forms too', function () {
    enableRecaptcha();
    Http::fake();
    $download = Download::factory()->create(['is_active' => true]);

    $this->post(route('newsletter.subscribe'), ['email' => 'subscriber@example.com'])
        ->assertSessionHasErrors('g-recaptcha-response');

    $this->post(route('downloads.request'), ['download_id' => $download->id, 'name' => 'Sam', 'phone' => '9876543210'])
        ->assertSessionHasErrors('g-recaptcha-response');

    $this->post(route('careers.submit'), ['con_name' => 'Sam', 'con_email' => 'sam@example.com', 'con_phone' => '9876543210'])
        ->assertSessionHasErrors('g-recaptcha-response');

    expect(Lead::count())->toBe(0);
});
