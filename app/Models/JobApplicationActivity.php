<?php

namespace App\Models;

use App\Enums\JobApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One entry in an application's audit trail. Written once, never edited.
 */
class JobApplicationActivity extends Model
{
    public const CREATED = 'created';

    public const IMPORTED = 'imported';

    public const STATUS_CHANGED = 'status_changed';

    public const ASSIGNED = 'assigned';

    public const NOTE_ADDED = 'note_added';

    public const ARCHIVED = 'archived';

    public const UNARCHIVED = 'unarchived';

    public const RESUME_DOWNLOADED = 'resume_downloaded';

    protected $fillable = ['job_application_id', 'user_id', 'type', 'old_value', 'new_value'];

    /**
     * @return BelongsTo<JobApplication, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Whether the entry was written by the website rather than a person.
     */
    public function isSystemEntry(): bool
    {
        return in_array($this->type, [self::CREATED, self::IMPORTED], true);
    }

    /**
     * A one-line, human-readable summary for the activity timeline.
     */
    public function description(): string
    {
        return match ($this->type) {
            self::CREATED => 'Application received from the careers page',
            self::IMPORTED => 'Moved over from Leads',
            self::STATUS_CHANGED => 'Status changed from '.$this->statusLabel($this->old_value).' to '.$this->statusLabel($this->new_value),
            self::ASSIGNED => $this->new_value
                ? 'Assigned to '.$this->new_value.($this->old_value ? ' (was '.$this->old_value.')' : '')
                : 'Unassigned'.($this->old_value ? ' from '.$this->old_value : ''),
            self::NOTE_ADDED => 'Added an internal note',
            self::ARCHIVED => 'Archived',
            self::UNARCHIVED => 'Restored from the archive',
            self::RESUME_DOWNLOADED => 'Downloaded the CV',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    private function statusLabel(?string $value): string
    {
        return JobApplicationStatus::tryFrom((string) $value)?->label() ?? (string) $value;
    }
}
