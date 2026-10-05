<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Database\Factories\LeadActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One entry in a lead's audit trail. Written once, never edited.
 */
class LeadActivity extends Model
{
    /** @use HasFactory<LeadActivityFactory> */
    use HasFactory;

    public const CREATED = 'created';

    public const STATUS_CHANGED = 'status_changed';

    public const ASSIGNED = 'assigned';

    public const NOTE_ADDED = 'note_added';

    public const DELETED = 'deleted';

    public const RESTORED = 'restored';

    protected $fillable = ['lead_id', 'user_id', 'type', 'old_value', 'new_value'];

    /**
     * @return BelongsTo<Lead, $this>
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A one-line, human-readable summary for the activity timeline.
     */
    public function description(): string
    {
        return match ($this->type) {
            self::CREATED => 'Lead received from the website',
            self::STATUS_CHANGED => 'Status changed from '.$this->statusLabel($this->old_value).' to '.$this->statusLabel($this->new_value),
            self::ASSIGNED => $this->new_value
                ? 'Assigned to '.$this->new_value.($this->old_value ? ' (was '.$this->old_value.')' : '')
                : 'Unassigned'.($this->old_value ? ' from '.$this->old_value : ''),
            self::NOTE_ADDED => 'Added an internal note',
            self::DELETED => 'Moved to trash',
            self::RESTORED => 'Restored from trash',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    private function statusLabel(?string $value): string
    {
        return LeadStatus::tryFrom((string) $value)?->label() ?? (string) $value;
    }
}
