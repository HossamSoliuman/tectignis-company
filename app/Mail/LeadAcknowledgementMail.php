<?php

namespace App\Mail;

use App\Mail\Concerns\DeliversSafely;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Optional "we received your enquiry" reply to the visitor (spec §26.4), sent
 * from the same verified website mailbox. Deliberately makes no response-time
 * promise.
 */
class LeadAcknowledgementMail extends Mailable
{
    use DeliversSafely, Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    /**
     * Send when switched on in Email & Forms and the visitor left an email on
     * a project enquiry.
     */
    public static function dispatchFor(Lead $lead): void
    {
        if (Setting::get('lead_acknowledgement_enabled') !== '1') {
            return;
        }

        if (! $lead->email || ! $lead->sourceEnum()?->isEnquiry()) {
            return;
        }

        (new self($lead))->deliverTo($lead->email);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We have received your enquiry – '.(Setting::get('site_name') ?: config('app.name')),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-acknowledgement',
        );
    }
}
