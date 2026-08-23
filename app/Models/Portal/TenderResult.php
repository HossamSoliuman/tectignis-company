<?php

namespace App\Models\Portal;

use App\Enums\Portal\TenderOutcome;
use App\Observers\Portal\ActivityLogObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * How a submitted tender ended (§7) — one row per tender, the input to win/loss
 * reporting in Phase 4.
 */
#[ObservedBy(ActivityLogObserver::class)]
class TenderResult extends Model
{
    protected $table = 'portal_tender_results';

    protected $fillable = [
        'tender_id',
        'technical_result',
        'financial_result',
        'outcome',
        'awarded_value',
        'remarks',
        'recorded_by_id',
    ];

    protected function casts(): array
    {
        return [
            'outcome' => TenderOutcome::class,
            'awarded_value' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<Tender, $this> */
    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'recorded_by_id');
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
