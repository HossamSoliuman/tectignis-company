<?php

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('valid enquiry creates a lead with every submitted field and shows the thank-you page', function () {
    $this->post(route('contact.submit'), validEnquiry(['page_url' => 'https://tectignis.test/services/cloud']))
        ->assertRedirect(route('contact.thank-you'));

    $lead = Lead::sole();

    expect($lead)
        ->name->toBe('Jane Doe')
        ->email->toBe('jane@acme.example')
        ->company->toBe('Acme Corp')
        ->country->toBe('United Arab Emirates')
        ->phone->toBe('+971501234567')
        ->service->toBe('Cloud Migration')
        ->budget->toBe('500-1k')
        ->timeline->toBe('1-3-months')
        ->source->toBe('contact')
        ->page_url->toBe('https://tectignis.test/services/cloud')
        ->status->toBe(LeadStatus::New)
        ->and($lead->consented_at)->not->toBeNull()
        ->and($lead->activities()->where('type', LeadActivity::CREATED)->exists())->toBeTrue();
});

it('the thank-you page acknowledges the submission', function () {
    $this->followingRedirects()
        ->post(route('contact.submit'), validEnquiry())
        ->assertOk()
        ->assertSee('Thank you, Jane!')
        ->assertSee('about Cloud Migration');
});

it('the thank-you page is only reachable straight after a submission', function () {
    $this->get(route('contact.thank-you'))->assertRedirect(route('contact'));
});

it('requires every mandatory enquiry field', function () {
    $this->post(route('contact.submit'), [])
        ->assertSessionHasErrors(['name', 'email', 'company', 'country', 'service', 'message', 'consent']);

    expect(Lead::count())->toBe(0);
});

it('phone, budget and timeline are optional', function () {
    $this->post(route('contact.submit'), validEnquiry(['phone' => null, 'budget' => null, 'timeline' => null]))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::count())->toBe(1);
});

it('rejects invalid enquiry values', function (string $field, mixed $value) {
    $this->post(route('contact.submit'), validEnquiry([$field => $value]))
        ->assertSessionHasErrors($field);

    expect(Lead::count())->toBe(0);
})->with([
    'malformed email' => ['email', 'not-an-email'],
    'unknown country' => ['country', 'Atlantis'],
    'unapproved service' => ['service', 'Time travel'],
    'too-short description' => ['message', 'Need help.'],
    'not a phone number' => ['phone', 'call me maybe'],
    'too short to be a number' => ['phone', '+971 50'],
    'unknown phone country' => ['phone_country', 'XX'],
    'unknown budget' => ['budget', 'a-lot'],
    'unknown timeline' => ['timeline', 'someday'],
    'consent not given' => ['consent', '0'],
]);

it('stores the phone number in E.164 form with the dialling code picked beside it', function (string $phoneCountry, string $phone, string $stored) {
    $this->post(route('contact.submit'), validEnquiry(['phone_country' => $phoneCountry, 'phone' => $phone]))
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::sole()->phone)->toBe($stored);
})->with([
    'national number' => ['IN', '98765 43210', '+919876543210'],
    'trunk zero dropped' => ['GB', '07400 123456', '+447400123456'],
    'italian leading zero kept' => ['IT', '06 1234 5678', '+390612345678'],
    'punctuation removed' => ['US', '(201) 555-0123', '+12015550123'],
    'already international' => ['IN', '+971 50 123 4567', '+971501234567'],
    '00 international prefix' => ['IN', '00971 50 123 4567', '+971501234567'],
]);

it('rejects a number that is not valid for the calling code picked beside it', function () {
    $this->post(route('contact.submit'), validEnquiry(['phone_country' => 'GB', 'phone' => '50 123 4567']))
        ->assertSessionHasErrors(['phone' => 'Please enter a valid phone number for the selected country code.']);

    expect(Lead::count())->toBe(0);
});

it('keeps the calling code separate from the business location', function () {
    $this->post(route('contact.submit'), validEnquiry(['country' => 'United Kingdom', 'phone_country' => 'AE', 'phone' => '50 123 4567']))
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::sole())
        ->country->toBe('United Kingdom')
        ->phone->toBe('+971501234567');
});

it('preselects the calling code of the country the CDN detected from the IP address', function () {
    $this->get(route('contact'), ['CF-IPCountry' => 'AE'])
        ->assertOk()
        ->assertSee('name="phone_country" value="AE"', false)
        ->assertSee('data-phone-detected ', false);
});

it('falls back to the default calling code when the country is not detected', function (?string $header) {
    $this->get(route('contact'), array_filter(['CF-IPCountry' => $header]))
        ->assertOk()
        ->assertSee('name="phone_country" value="IN"', false)
        ->assertDontSee('data-phone-detected ', false);
})->with(['no header' => null, 'unknown country' => 'XX', 'Tor exit node' => 'T1']);

it('never replaces the calling code the visitor picked with the detected one', function () {
    $this->from(route('contact'))
        ->post(route('contact.submit'), validEnquiry(['form_id' => 'contact-page', 'phone_country' => 'GB', 'phone' => '7400 123456', 'company' => '']))
        ->assertRedirect(route('contact'));

    $this->get(route('contact'), ['CF-IPCountry' => 'AE'])
        ->assertOk()
        ->assertSee('name="phone_country" value="GB"', false);
});

it('refills the phone number and its country code after a validation error', function () {
    $this->from(route('contact'))
        ->post(route('contact.submit'), validEnquiry(['form_id' => 'consultation-modal', 'phone_country' => 'AE', 'phone' => '50 123 4567', 'company' => '']))
        ->assertRedirect(route('contact'));

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('name="phone_country" value="AE"', false)
        ->assertSee('value="50 123 4567"', false);
});

it('consultation modal submissions are stored as consultation leads', function () {
    $this->post(route('contact.submit'), validEnquiry(['source' => 'consultation', 'form_id' => 'consultation-modal']))
        ->assertRedirect(route('contact.thank-you'));

    $this->assertDatabaseHas('leads', ['email' => 'jane@acme.example', 'source' => 'consultation']);
});

it('validation errors re-open only the form that was submitted', function () {
    $this->from(route('contact'))
        ->post(route('contact.submit'), validEnquiry(['form_id' => 'consultation-modal', 'source' => 'consultation', 'company' => '']))
        ->assertRedirect(route('contact'));

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('data-open="true"', false);
});

it('stores UTM parameters captured earlier in the session', function () {
    $this->get(route('home', ['utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'uae-cloud', 'utm_term' => 'azure migration']))
        ->assertOk();

    $this->get(route('contact'))->assertOk();

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect();

    expect(Lead::sole())
        ->utm_source->toBe('google')
        ->utm_medium->toBe('cpc')
        ->utm_campaign->toBe('uae-cloud')
        ->utm_term->toBe('azure migration')
        ->utm_content->toBeNull();
});

it('stores the Google Ads click and campaign IDs captured earlier in the session', function () {
    $this->get(route('home', ['gclid' => 'Cj0KCQjw-test', 'gad_source' => '1', 'gad_campaignid' => '21436587']))->assertOk();

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect();

    expect(Lead::sole())
        ->gclid->toBe('Cj0KCQjw-test')
        ->gad_campaignid->toBe('21436587')
        ->gbraid->toBeNull()
        ->utm_source->toBeNull();
});

it('credits the most recent campaign click', function () {
    $this->get(route('home', ['utm_source' => 'linkedin', 'utm_campaign' => 'uk-saas']))->assertOk();
    $this->get(route('home', ['gbraid' => '0AAAAA-ios', 'gad_campaignid' => '99887766']))->assertOk();

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect();

    expect(Lead::sole())
        ->gbraid->toBe('0AAAAA-ios')
        ->gad_campaignid->toBe('99887766')
        ->utm_source->toBeNull()
        ->utm_campaign->toBeNull();
});

it('falls back to the referring page when no page url is posted', function () {
    $this->from(route('contact'))->post(route('contact.submit'), validEnquiry())->assertRedirect();

    expect(Lead::sole()->page_url)->toBe(route('contact'));
});

it('renders the enquiry form on the contact page', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('name="company"', false)
        ->assertSee('name="country"', false)
        ->assertSee('name="phone_country"', false)
        ->assertSee('name="consent"', false)
        ->assertSee('Cloud Migration');
});
