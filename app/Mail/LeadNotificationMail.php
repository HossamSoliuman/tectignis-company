<?php

namespace App\Mail;

use App\Mail\Concerns\DeliversSafely;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Internal notification to the sales/lead mailbox (spec §26.3). Sent From the
 * authenticated website mailbox with the visitor as Reply-To, never as From.
 */
class LeadNotificationMail extends Mailable
{
    use DeliversSafely, Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    /**
     * Notify the mailbox configured for the lead's source, and acknowledge the
     * visitor when that is switched on. Failures are logged but never bubble up
     * so a form submission is never interrupted by a mail/transport error.
     */
    public static function dispatchFor(Lead $lead): void
    {
        self::notify($lead);

        LeadAcknowledgementMail::dispatchFor($lead);
    }

    /**
     * Send (or resend) only the internal notification. A missing mailbox is
     * recorded as a failure so it shows in the admin.
     */
    public static function notify(Lead $lead): void
    {
        $recipient = self::recipientFor($lead->source);

        if (! $recipient) {
            $lead->recordNotificationDelivery('No notification mailbox is configured.');

            return;
        }

        (new self($lead))->deliverTo($recipient);
    }

    /**
     * The configured recipient address for a given lead source, falling back
     * to the generic recipient and then the public site email.
     */
    public static function recipientFor(?string $source): ?string
    {
        $value = $source ? Setting::get('mail_to_'.$source) : null;

        return $value
            ?: Setting::get('mail_to_default')
            ?: Setting::get('site_email');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine(),
            replyTo: $this->lead->email ? [new Address($this->lead->email, $this->lead->name)] : [],
        );
    }

    /**
     * "New Website Lead – {Service} – {Company}" for project enquiries; other
     * forms keep a source-specific subject.
     */
    public function subjectLine(): string
    {
        if ($this->lead->sourceEnum()?->isEnquiry()) {
            return 'New Website Lead – '.($this->lead->service ?: 'General Enquiry').' – '.($this->lead->company ?: $this->lead->name);
        }

        return 'New '.$this->lead->sourceLabel().': '.($this->lead->subject ?: $this->lead->name);
    }

    protected function recordDelivery(?string $error): void
    {
        $this->lead->recordNotificationDelivery($error);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-notification',
        );
    }
}
