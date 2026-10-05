<?php

namespace App\Mail\Concerns;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Lead-related mail must never break or undo a form submission (spec §26.4):
 * the lead is already stored, so delivery problems are only logged.
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
            'lead_id' => $this->lead->id ?? null,
            'error' => $exception->getMessage(),
        ]);
    }
}
