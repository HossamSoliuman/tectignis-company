<?php

namespace App\Models\Portal;

use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\OemRequirementType;
use App\Observers\Portal\ActivityLogObserver;
use App\Observers\Portal\TenderProgressObserver;
use App\Policies\Portal\OemFollowupPolicy;
use Database\Factories\Portal\OemFollowupFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Something we are chasing from a manufacturer — an MAF, an authorisation, a
 * quotation (§11).
 *
 * The current state lives here; every contact attempt is appended to
 * `followupUpdates` and never overwritten, because "we called them three times"
 * is exactly the fact management needs when a bid is at risk.
 */
#[ObservedBy([ActivityLogObserver::class, TenderProgressObserver::class])]
#[UsePolicy(OemFollowupPolicy::class)]
class OemFollowup extends Model
{
    /** @use HasFactory<OemFollowupFactory> */
    use HasFactory;

    protected $table = 'portal_oem_followups';

    protected $fillable = [
        'tender_id',
        'oem_name',
        'requirement_type',
        'product',
        'requested_by_id',
        'requested_on',
        'required_by',
        'contact_person',
        'contact_channel',
        'status',
        'next_followup_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'requirement_type' => OemRequirementType::class,
            'status' => OemFollowupStatus::class,
            'requested_on' => 'date',
            'required_by' => 'datetime',
            'next_followup_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Tender, $this> */
    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'requested_by_id');
    }

    /**
     * The append-only contact history (§11).
     *
     * @return MorphMany<FollowupUpdate, $this>
     */
    public function followupUpdates(): MorphMany
    {
        return $this->morphMany(FollowupUpdate::class, 'followupable');
    }

    /** @return MorphMany<Attachment, $this> */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /** @param  Builder<self>  $query */
    public function scopeOpen(Builder $query): void
    {
        $query->whereNotIn('status', array_column(OemFollowupStatus::closed(), 'value'));
    }

    /**
     * Still open with a chase date that has already passed, or a required-by
     * date gone — the OEM items that need a phone call today.
     *
     * @param  Builder<self>  $query
     */
    public function scopeDue(Builder $query): void
    {
        $query->open()->where(function (Builder $builder): void {
            $builder->where('next_followup_at', '<=', now())
                ->orWhere('required_by', '<', now());
        });
    }

    /** @param  Builder<self>  $query */
    public function scopeDueWithin(Builder $query, int $days): void
    {
        $query->open()
            ->whereNotNull('next_followup_at')
            ->where('next_followup_at', '<=', now()->addDays($days)->endOfDay());
    }

    /**
     * Late: the OEM has not delivered and the date we needed it by has gone.
     */
    public function isOverdue(): bool
    {
        return ! $this->status->isClosed()
            && $this->required_by !== null
            && $this->required_by->isPast();
    }

    public function isChaseDue(): bool
    {
        return ! $this->status->isClosed()
            && $this->next_followup_at !== null
            && $this->next_followup_at->isPast();
    }

    /**
     * Chasing an OEM is tender work, so its files follow the tender's policy.
     * Standalone items (no tender) authorize against themselves.
     */
    public function attachmentAuthority(): Tender|self
    {
        return $this->tender ?? $this;
    }
}
