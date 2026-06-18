<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Human-readable labels per lead source, used in the subject line.
     *
     * @var array<string, string>
     */
    private const SOURCE_LABELS = [
        'contact' => 'Contact Enquiry',
        'consultation' => 'Consultation Request',
        'career' => 'Job Application',
        'newsletter' => 'Newsletter Subscription',
    ];

    public function __construct(public Lead $lead) {}

    /**
     * Resolve the recipient for a lead's source and send the notification.
     * Failures are logged but never bubble up so a form submission is never
     * interrupted by a mail/transport error.
     */
    public static function dispatchFor(Lead $lead): void
    {
        $recipient = self::recipientFor($lead->source);

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new self($lead));
        } catch (\Throwable $e) {
            Log::error('Failed to send lead notification email', [
                'lead_id' => $lead->id,
                'source' => $lead->source,
                'error' => $e->getMessage(),
            ]);
        }
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
        $label = self::SOURCE_LABELS[$this->lead->source] ?? 'Website Enquiry';

        return new Envelope(
            subject: 'New '.$label.': '.($this->lead->subject ?: $this->lead->name),
            replyTo: $this->lead->email ? [new Address($this->lead->email, $this->lead->name)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-notification',
        );
    }
}
