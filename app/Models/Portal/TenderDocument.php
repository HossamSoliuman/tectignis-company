<?php

namespace App\Models\Portal;

use App\Enums\Portal\TenderDocumentCategory;
use App\Enums\Portal\TenderDocumentStatus;
use App\Observers\Portal\ActivityLogObserver;
use App\Observers\Portal\TenderProgressObserver;
use Database\Factories\Portal\TenderDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * A required line on a tender's document checklist (§10).
 *
 * The row is the requirement; the file that satisfies it is an Attachment.
 * Re-uploading keeps every earlier version attached, so a rejected revision can
 * still be produced later.
 */
#[ObservedBy([ActivityLogObserver::class, TenderProgressObserver::class])]
class TenderDocument extends Model
{
    /** @use HasFactory<TenderDocumentFactory> */
    use HasFactory;

    protected $table = 'portal_tender_documents';

    protected $fillable = [
        'tender_id',
        'name',
        'category',
        'is_required',
        'status',
        'responsible_employee_id',
        'requested_on',
        'required_by',
        'attachment_id',
        'version',
        'expires_on',
        'remarks',
        'verified_by_id',
        'verified_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'category' => TenderDocumentCategory::class,
            'status' => TenderDocumentStatus::class,
            'is_required' => 'boolean',
            'requested_on' => 'date',
            'required_by' => 'datetime',
            'version' => 'integer',
            'expires_on' => 'date',
            'verified_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    /** @return BelongsTo<Tender, $this> */
    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_employee_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'verified_by_id');
    }

    /**
     * The file currently satisfying this requirement.
     *
     * @return BelongsTo<Attachment, $this>
     */
    public function attachment(): BelongsTo
    {
        return $this->belongsTo(Attachment::class, 'attachment_id');
    }

    /**
     * Every file ever uploaded against this requirement, newest version last.
     *
     * @return MorphMany<Attachment, $this>
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Mandatory lines that are not yet Ready or Uploaded — the gap that blocks
     * submission and drives the dashboard's "Tender Document Gaps".
     *
     * @param  Builder<self>  $query
     */
    public function scopeMissingMandatory(Builder $query): void
    {
        $query->where('is_required', true)
            ->whereNotIn('status', array_column(TenderDocumentStatus::settled(), 'value'));
    }

    /** @param  Builder<self>  $query */
    public function scopeSettled(Builder $query): void
    {
        $query->whereIn('status', array_column(TenderDocumentStatus::settled(), 'value'));
    }

    /**
     * A validity date that has already passed makes the document useless even
     * though it is on file — surfaced rather than silently accepted.
     */
    public function isExpired(): bool
    {
        return $this->expires_on !== null && $this->expires_on->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Files here are as confidential as the tender itself, so access is decided
     * by the tender's policy.
     */
    public function attachmentAuthority(): Tender
    {
        return $this->tender;
    }
}
