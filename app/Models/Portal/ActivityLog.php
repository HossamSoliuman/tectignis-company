<?php

namespace App\Models\Portal;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Append-only audit trail for every portal record.
 *
 * Rows are written by ActivityLogObserver and are never updated or deleted —
 * the model has no `updated_at`, and no route exposes a write path other than
 * the observer itself.
 */
class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'portal_activity_logs';

    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'event',
        'description',
        'changes',
        'file_reference',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return MorphTo<Model, $this> */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /** @param  Builder<self>  $query */
    public function scopeForSubject(Builder $query, Model $subject): void
    {
        $query->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Short, human-readable label for the record this entry refers to.
     */
    public function subjectLabel(): string
    {
        return class_basename((string) $this->subject_type).' #'.$this->subject_id;
    }
}
