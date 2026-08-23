<?php

namespace App\Models\Portal;

use App\Enums\Portal\ClarificationStatus;
use App\Observers\Portal\ActivityLogObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * A pre-bid question put to the tendering authority and the answer that came
 * back (§13). Unanswered questions close in on the deadline, so they are
 * tracked rather than remembered.
 */
#[ObservedBy(ActivityLogObserver::class)]
class TenderClarification extends Model
{
    protected $table = 'portal_tender_clarifications';

    protected $fillable = [
        'tender_id',
        'question',
        'raised_by_id',
        'raised_on',
        'submitted_through',
        'response',
        'response_on',
        'status',
        'attachment_id',
    ];

    protected function casts(): array
    {
        return [
            'raised_on' => 'date',
            'response_on' => 'date',
            'status' => ClarificationStatus::class,
        ];
    }

    /** @return BelongsTo<Tender, $this> */
    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'raised_by_id');
    }

    /** @return BelongsTo<Attachment, $this> */
    public function attachment(): BelongsTo
    {
        return $this->belongsTo(Attachment::class, 'attachment_id');
    }

    /** @return MorphMany<Attachment, $this> */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function attachmentAuthority(): Tender
    {
        return $this->tender;
    }
}
