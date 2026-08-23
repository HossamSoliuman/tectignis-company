<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * One contact attempt in a follow-up history — append-only by design (§11).
 *
 * Polymorphic so OEM follow-ups use it today and sales follow-ups reuse it in
 * Phase 3 without a second implementation of the same timeline.
 */
class FollowupUpdate extends Model
{
    protected $table = 'portal_followup_updates';

    protected $fillable = [
        'followupable_type',
        'followupable_id',
        'employee_id',
        'note',
        'status_from',
        'status_to',
        'contacted_on',
        'next_followup_at',
    ];

    protected function casts(): array
    {
        return [
            'contacted_on' => 'datetime',
            'next_followup_at' => 'datetime',
        ];
    }

    /** @return MorphTo<Model, $this> */
    public function followupable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<Employee, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /** @return MorphMany<Attachment, $this> */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * A history entry has no visibility of its own; it inherits whatever the
     * item being chased answers.
     */
    public function attachmentAuthority(): ?Model
    {
        $parent = $this->followupable;

        return $parent !== null && method_exists($parent, 'attachmentAuthority')
            ? $parent->attachmentAuthority()
            : $parent;
    }
}
