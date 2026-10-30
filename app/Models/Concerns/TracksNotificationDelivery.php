<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Delivery state of a record's internal notification email: when it went out,
 * or when and why it last failed. Written without touching updated_at or
 * firing model events, so it never reads as an edit to the record.
 *
 * @property Carbon|null $notified_at
 * @property Carbon|null $notification_failed_at
 * @property string|null $notification_error
 */
trait TracksNotificationDelivery
{
    public function initializeTracksNotificationDelivery(): void
    {
        $this->mergeCasts([
            'notified_at' => 'datetime',
            'notification_failed_at' => 'datetime',
        ]);
    }

    /**
     * Record the outcome of a notification attempt: null for delivered, or
     * the reason it failed.
     */
    public function recordNotificationDelivery(?string $error = null): void
    {
        $attributes = $error === null
            ? ['notified_at' => now(), 'notification_failed_at' => null, 'notification_error' => null]
            : ['notification_failed_at' => now(), 'notification_error' => Str::limit($error, 480)];

        static::withoutTimestamps(fn () => $this->forceFill($attributes)->saveQuietly());
    }

    public function notificationFailed(): bool
    {
        return $this->notification_failed_at !== null;
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWhereNotificationFailed(Builder $query): void
    {
        $query->whereNotNull('notification_failed_at');
    }
}
