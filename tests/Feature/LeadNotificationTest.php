<?php

use App\Mail\LeadAcknowledgementMail;
use App\Mail\LeadNotificationMail;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setting::set('mail_to_contact', 'sales@tectignis.example', 'mail');
});

it('notifies the sales mailbox with the spec subject and the visitor as reply-to', function () {
    Mail::fake();

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect();

    Mail::assertSent(LeadNotificationMail::class, function (LeadNotificationMail $mail): bool {
        return $mail->hasTo('sales@tectignis.example')
            && $mail->hasReplyTo('jane@acme.example')
            && $mail->subjectLine() === 'New Website Lead – Cloud Migration – Acme Corp';
    });
});

it('includes qualification, attribution and an admin link in the notification', function () {
    $lead = Lead::factory()->enquiry()->create([
        'company' => 'Globex',
        'country' => 'Canada',
        'budget' => '10k-25k',
        'timeline' => 'immediate',
        'utm_source' => 'linkedin',
        'utm_campaign' => 'canada-ai',
    ]);

    $html = (new LeadNotificationMail($lead))->render();

    expect($html)
        ->toContain('Globex')
        ->toContain('Canada')
        ->toContain('US$10,000 – 25,000')
        ->toContain('Immediate')
        ->toContain('linkedin')
        ->toContain('canada-ai')
        ->toContain(route('admin.leads.show', $lead));
});

it('keeps the lead and logs the error when SMTP fails', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('Connection refused'));
    Log::spy();

    $this->post(route('contact.submit'), validEnquiry())
        ->assertRedirect(route('contact.thank-you'));

    expect(Lead::count())->toBe(1);

    Log::shouldHaveReceived('error')
        ->withArgs(fn (string $message, array $context): bool => str_contains($message, 'LeadNotificationMail') && $context['error'] === 'Connection refused')
        ->once();
});

it('queues lead emails when the queue is enabled for them', function () {
    Mail::fake();
    config(['mail.queue_lead_emails' => true]);

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect();

    Mail::assertQueued(LeadNotificationMail::class);
    Mail::assertNotSent(LeadNotificationMail::class);
});

it('queued lead emails retry before giving up', function () {
    $mail = new LeadNotificationMail(Lead::factory()->create());

    expect($mail->tries)->toBe(3)->and($mail->backoff)->toBe([60, 300]);
});

it('acknowledges the visitor only when switched on', function () {
    Mail::fake();

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect();
    Mail::assertNotSent(LeadAcknowledgementMail::class);

    Setting::set('lead_acknowledgement_enabled', '1', 'mail');

    $this->post(route('contact.submit'), validEnquiry(['email' => 'second@acme.example']))->assertRedirect();
    Mail::assertSent(LeadAcknowledgementMail::class, fn (LeadAcknowledgementMail $mail): bool => $mail->hasTo('second@acme.example'));
});

it('the acknowledgement makes no response-time promise', function () {
    $html = (new LeadAcknowledgementMail(Lead::factory()->enquiry()->create()))->render();

    expect($html)
        ->toContain('We have received your enquiry')
        ->not->toContain('24 hours')
        ->not->toContain('business day');
});

it('admin can switch the visitor acknowledgement on', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->put(route('admin.mail.update'), ['lead_acknowledgement_enabled' => '1'])
        ->assertRedirect();

    expect(Setting::get('lead_acknowledgement_enabled'))->toBe('1');
});
