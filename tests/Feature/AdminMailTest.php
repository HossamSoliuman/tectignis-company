<?php

use App\Mail\LeadNotificationMail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function mailAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

it('admin can view the mail settings page', function () {
    $this->actingAs(mailAdmin())
        ->get(route('admin.mail.edit'))
        ->assertOk()
        ->assertSee('Email & Forms')
        ->assertSee('Send a Test Email');
});

it('admin can save smtp and recipient settings', function () {
    $this->actingAs(mailAdmin())
        ->put(route('admin.mail.update'), [
            'smtp_host' => 'smtp.example.com',
            'smtp_port' => '587',
            'smtp_username' => 'mailer@example.com',
            'smtp_password' => 'secret',
            'smtp_encryption' => 'tls',
            'smtp_from_address' => 'no-reply@example.com',
            'smtp_from_name' => 'Example',
            'mail_to_default' => 'inbox@example.com',
            'mail_to_contact' => 'sales@example.com',
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    expect(Setting::get('smtp_host'))->toBe('smtp.example.com');
    expect(Setting::get('smtp_port'))->toBe('587');
    expect(Setting::get('mail_to_contact'))->toBe('sales@example.com');
    expect(Setting::get('mail_to_default'))->toBe('inbox@example.com');
});

it('rejects an invalid recipient email', function () {
    $this->actingAs(mailAdmin())
        ->put(route('admin.mail.update'), ['mail_to_contact' => 'not-an-email'])
        ->assertSessionHasErrors('mail_to_contact');
});

it('admin can send a test email', function () {
    Mail::fake();

    $this->actingAs(mailAdmin())
        ->post(route('admin.mail.test'), ['test_email' => 'check@example.com'])
        ->assertRedirect()
        ->assertSessionHas('status');
});

it('emails the configured contact recipient on contact submission', function () {
    Mail::fake();
    Setting::set('mail_to_contact', 'sales@example.com', 'mail');

    $this->post(route('contact.submit'), [
        'con_name' => 'Jane Doe',
        'con_email' => 'jane@example.com',
        'con_message' => 'I need a quote.',
    ])->assertRedirect();

    Mail::assertSent(LeadNotificationMail::class, fn (LeadNotificationMail $mail) => $mail->hasTo('sales@example.com'));
});

it('falls back to the default recipient when a form has no specific address', function () {
    Mail::fake();
    Setting::set('mail_to_default', 'inbox@example.com', 'mail');

    $this->post(route('newsletter.subscribe'), [
        'email' => 'subscriber@example.com',
    ])->assertRedirect();

    Mail::assertSent(LeadNotificationMail::class, fn (LeadNotificationMail $mail) => $mail->hasTo('inbox@example.com'));
});

it('does not send a notification when no recipient is configured', function () {
    Mail::fake();

    $this->post(route('newsletter.subscribe'), [
        'email' => 'subscriber@example.com',
    ])->assertRedirect();

    Mail::assertNothingSent();
});
