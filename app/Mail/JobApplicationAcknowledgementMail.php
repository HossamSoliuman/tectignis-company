<?php

namespace App\Mail;

use App\Mail\Concerns\DeliversSafely;
use App\Models\JobApplication;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * "We received your application" reply to the candidate (spec §26.11). On
 * unless switched off under Email & Forms. Makes no promise about timing or
 * outcome.
 */
class JobApplicationAcknowledgementMail extends Mailable
{
    use DeliversSafely, Queueable, SerializesModels;

    public function __construct(public JobApplication $application) {}

    public static function isEnabled(): bool
    {
        return Setting::get('application_acknowledgement_enabled') !== '0';
    }

    public static function dispatchFor(JobApplication $application): void
    {
        if (self::isEnabled()) {
            (new self($application))->deliverTo($application->email);
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We have received your application – '.(Setting::get('site_name') ?: config('app.name')),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-application-acknowledgement',
        );
    }

    /**
     * @return array<string, int|null>
     */
    protected function failureContext(): array
    {
        return ['job_application_id' => $this->application->id];
    }
}
