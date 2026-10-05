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
        ->phone->toBe('+971 50 123 4567')
        ->service->toBe('Cloud Migration')
        ->budget->toBe('5k-10k')
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
    'non-international phone' => ['phone', 'call me maybe'],
    'unknown budget' => ['budget', 'a-lot'],
    'unknown timeline' => ['timeline', 'someday'],
    'consent not given' => ['consent', '0'],
]);

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

it('falls back to the referring page when no page url is posted', function () {
    $this->from(route('contact'))->post(route('contact.submit'), validEnquiry())->assertRedirect();

    expect(Lead::sole()->page_url)->toBe(route('contact'));
});

it('renders the enquiry form on the contact page', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('name="company"', false)
        ->assertSee('name="country"', false)
        ->assertSee('name="consent"', false)
        ->assertSee('Cloud Migration');
});
