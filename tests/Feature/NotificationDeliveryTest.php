<?php

use App\Mail\LeadNotificationMail;
use App\Models\JobApplication;
use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Setting::set('mail_to_contact', 'sales@tectignis.example', 'mail');
    Setting::set('mail_to_career', 'hr@tectignis.example', 'mail');
});

it('records when the lead notification is delivered', function () {
    $this->post(route('contact.submit'), validEnquiry())->assertRedirect(route('contact.thank-you'));

    $lead = Lead::sole();

    expect($lead->notified_at)->not->toBeNull()
        ->and($lead->notificationFailed())->toBeFalse();
});

it('records an SMTP failure on the lead without losing it', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('Connection refused'));

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect(route('contact.thank-you'));

    $lead = Lead::sole();

    expect($lead->notificationFailed())->toBeTrue()
        ->and($lead->notification_error)->toBe('Connection refused');
});

it('records a failure when no notification mailbox is configured', function () {
    foreach (['mail_to_contact', 'mail_to_default', 'site_email'] as $key) {
        Setting::set($key, '', 'mail');
    }

    $this->post(route('contact.submit'), validEnquiry())->assertRedirect();

    expect(Lead::sole()->notification_error)->toBe('No notification mailbox is configured.');
});

it('records the final failure of a queued notification', function () {
    $lead = Lead::factory()->create();

    (new LeadNotificationMail($lead))->failed(new RuntimeException('Timed out'));

    expect($lead->refresh()->notificationFailed())->toBeTrue()
        ->and($lead->notification_error)->toBe('Timed out');
});

it('shows failed notifications in the leads list and on the dashboard', function () {
    $failed = Lead::factory()->create(['name' => 'Failed Fiona']);
    $failed->recordNotificationDelivery('Connection refused');
    Lead::factory()->create(['name' => 'Delivered Dan']);
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.leads.index', ['notification' => 'failed']))
        ->assertOk()
        ->assertSee('Failed Fiona')
        ->assertSee('Email failed')
        ->assertDontSee('Delivered Dan');

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('1 lead notification email failed');

    $this->actingAs($admin)
        ->get(route('admin.leads.show', $failed))
        ->assertSee('Connection refused')
        ->assertSee('Resend notification');
});

it('resends a failed lead notification', function () {
    $lead = Lead::factory()->create(['source' => 'contact']);
    $lead->recordNotificationDelivery('Connection refused');

    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->from(route('admin.leads.show', $lead))
        ->post(route('admin.leads.notification.resend', $lead))
        ->assertRedirect(route('admin.leads.show', $lead))
        ->assertSessionHas('status', 'Notification email sent.');

    expect($lead->refresh()->notificationFailed())->toBeFalse()
        ->and($lead->notified_at)->not->toBeNull();
});

it('does not let read-only users resend', function () {
    $lead = Lead::factory()->create();

    $this->actingAs(User::factory()->create(['role' => 'readonly']))
        ->post(route('admin.leads.notification.resend', $lead))
        ->assertForbidden();
});

it('records and resends the HR notification for a job application', function () {
    $application = JobApplication::factory()->create();
    $application->recordNotificationDelivery('Connection refused');

    $this->actingAs(User::factory()->create(['role' => 'hr']))
        ->get(route('admin.job-applications.index', ['notification' => 'failed']))
        ->assertOk()
        ->assertSee($application->name);

    $this->actingAs(User::factory()->create(['role' => 'hr']))
        ->post(route('admin.job-applications.notification.resend', $application))
        ->assertSessionHas('status', 'Notification email sent.');

    expect($application->refresh()->notificationFailed())->toBeFalse();
});
