<?php

namespace App\Models\Portal;

use App\Observers\Portal\ActivityLogObserver;
use App\Observers\Portal\TenderProgressObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One line of the final pre-submission checklist (§14), seeded per tender.
 *
 * Ticking a line records who ticked it and when — this is the list that stops a
 * bid being uploaded with the price sheet in the technical envelope.
 */
#[ObservedBy([ActivityLogObserver::class, TenderProgressObserver::class])]
class TenderSubmissionCheck extends Model
{
    protected $table = 'portal_tender_submission_checks';

    protected $fillable = [
        'tender_id',
        'label',
        'is_checked',
        'checked_by_id',
        'checked_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_checked' => 'boolean',
            'checked_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    /** @return BelongsTo<Tender, $this> */
    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'checked_by_id');
    }
}
