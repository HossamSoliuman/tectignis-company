<?php

namespace App\Mail;

use App\Mail\Concerns\DeliversSafely;
use App\Models\JobApplication;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Internal notification to the HR/recruitment mailbox (spec §26.11), separate
 * from lead mail in recipient, subject and template. The CV is never attached:
 * HR opens it from the Careers admin, behind sign-in.
 */
class JobApplicationNotificationMail extends Mailable
{
    use DeliversSafely, Queueable, SerializesModels;

    public function __construct(public JobApplication $application) {}

    /**
     * Notify HR, then acknowledge the candidate. Failures are logged but never
     * bubble up, so a submission is never lost to a mail/transport error.
     */
    public static function dispatchFor(JobApplication $application): void
    {
        $recipient = self::recipient();

        if ($recipient) {
            (new self($application))->deliverTo($recipient);
        }

        JobApplicationAcknowledgementMail::dispatchFor($application);
    }

    /**
     * The HR mailbox, falling back to the generic recipient and then the
     * public site email — never the sales (project enquiry) mailbox.
     */
    public static function recipient(): ?string
    {
        return Setting::get('mail_to_career')
            ?: Setting::get('mail_to_default')
            ?: Setting::get('site_email');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine(),
            replyTo: [new Address($this->application->email, $this->application->name)],
        );
    }

    /**
     * "New Job Application – {Position} – {Candidate}".
     */
    public function subjectLine(): string
    {
        $position = $this->application->job_title ?: ($this->application->designation_name ?: 'General Application');

        return 'New Job Application – '.$position.' – '.$this->application->name;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-application-notification',
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
