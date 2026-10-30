<?php

namespace App\Mail\Concerns;

use Illuminate\Contracts\Mail\Factory;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Form notification mail must never break or undo a submission (spec §26.4,
 * §26.11): the lead or application is already stored, so delivery problems
 * are logged and recorded on it ({@see recordDelivery()}) rather than thrown.
 *
 * With `mail.queue_lead_emails` on, mail goes to the queue and is retried
 * ({@see $tries}, {@see $backoff}); a final failure lands in {@see failed()}.
 */
trait DeliversSafely
{
    /**
     * Attempts made by the queue worker before giving up.
     */
    public int $tries = 3;

    /**
     * Seconds to wait between queued retries.
     *
     * @var list<int>
     */
    public array $backoff = [60, 300];

    /**
     * Send through the mailer, then record the delivery. Called directly for
     * immediate mail and by the queue worker for queued mail.
     *
     * @param  Factory|Mailer  $mailer
     */
    public function send($mailer): ?SentMessage
    {
        $sent = parent::send($mailer);

        $this->recordDelivery(null);

        return $sent;
    }

    /**
     * Queue or send this mailable, logging (never throwing) on failure.
     */
    protected function deliverTo(string $recipient): void
    {
        try {
            if (config('mail.queue_lead_emails')) {
                Mail::to($recipient)->queue($this);
            } else {
                Mail::to($recipient)->send($this);
            }
        } catch (Throwable $e) {
            $this->failed($e);
        }
    }

    /**
     * Log a delivery failure without exposing credentials or message bodies.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Failed to send '.class_basename($this), [
            ...$this->failureContext(),
            'error' => $exception->getMessage(),
        ]);

        $this->recordDelivery($exception->getMessage());
    }

    /**
     * Identifies the stored record in the failure log entry.
     *
     * @return array<string, int|null>
     */
    protected function failureContext(): array
    {
        return ['lead_id' => $this->lead->id ?? null];
    }

    /**
     * Store the outcome on the record: null for delivered, or the error.
     * Acknowledgements to the visitor are not tracked.
     */
    protected function recordDelivery(?string $error): void
    {
        //
    }
}
