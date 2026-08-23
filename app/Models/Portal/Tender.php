<?php

namespace App\Models\Portal;

use App\Enums\Portal\EligibilityStatus;
use App\Enums\Portal\TenderDecision;
use App\Enums\Portal\TenderPortalSource;
use App\Enums\Portal\TenderStage;
use App\Observers\Portal\ActivityLogObserver;
use App\Policies\Portal\TenderPolicy;
use Database\Factories\Portal\TenderFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * A bid opportunity and the workspace everything about it hangs off (§7, §27).
 *
 * The tender itself carries only the facts of the opportunity; eligibility,
 * documents, OEM chasing, tasks, clarifications, submission and result each
 * live in their own table so history survives and one screen can never
 * overwrite another's work.
 */
#[ObservedBy(ActivityLogObserver::class)]
#[UsePolicy(TenderPolicy::class)]
class Tender extends Model
{
    /** @use HasFactory<TenderFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'portal_tenders';

    protected $fillable = [
        'code',
        'tender_number',
        'title',
        'customer_organization',
        'portal',
        'tender_url',
        'published_at',
        'pre_bid_at',
        'submission_start_at',
        'submission_deadline_at',
        'estimated_value',
        'emd_required',
        'emd_amount',
        'fee_required',
        'fee_amount',
        'assigned_employee_id',
        'technical_owner_id',
        'sales_owner_id',
        'stage',
        'decision',
        'eligibility_status',
        'eligibility_override_reason',
        'notes',
        'completion_percent',
    ];

    protected function casts(): array
    {
        return [
            'portal' => TenderPortalSource::class,
            'published_at' => 'datetime',
            'pre_bid_at' => 'datetime',
            'submission_start_at' => 'datetime',
            'submission_deadline_at' => 'datetime',
            'estimated_value' => 'decimal:2',
            'emd_required' => 'boolean',
            'emd_amount' => 'decimal:2',
            'fee_required' => 'boolean',
            'fee_amount' => 'decimal:2',
            'stage' => TenderStage::class,
            'decision' => TenderDecision::class,
            'eligibility_status' => EligibilityStatus::class,
            'completion_percent' => 'integer',
        ];
    }

    /** @return BelongsTo<Employee, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function technicalOwner(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'technical_owner_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function salesOwner(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'sales_owner_id');
    }

    /** @return HasMany<TenderChecklistItem, $this> */
    public function checklistItems(): HasMany
    {
        return $this->hasMany(TenderChecklistItem::class, 'tender_id')->orderBy('sort_order');
    }

    /** @return HasMany<TenderDocument, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(TenderDocument::class, 'tender_id')->orderBy('sort_order');
    }

    /** @return HasMany<OemFollowup, $this> */
    public function oemFollowups(): HasMany
    {
        return $this->hasMany(OemFollowup::class, 'tender_id');
    }

    /** @return HasMany<TenderClarification, $this> */
    public function clarifications(): HasMany
    {
        return $this->hasMany(TenderClarification::class, 'tender_id');
    }

    /** @return HasMany<TenderSubmissionCheck, $this> */
    public function submissionChecks(): HasMany
    {
        return $this->hasMany(TenderSubmissionCheck::class, 'tender_id')->orderBy('sort_order');
    }

    /** @return HasOne<TenderResult, $this> */
    public function result(): HasOne
    {
        return $this->hasOne(TenderResult::class, 'tender_id');
    }

    /**
     * Tender work reuses `portal_tasks` through the `related` morph (§2.8), so
     * there is exactly one overdue engine and one "my work" list.
     *
     * @return MorphMany<Task, $this>
     */
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'related');
    }

    /** @return MorphMany<Attachment, $this> */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /** @return MorphMany<Comment, $this> */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Still being worked on — the tenders that belong on a dashboard.
     *
     * @param  Builder<self>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('stage', '!=', TenderStage::Closed)
            ->where('decision', '!=', TenderDecision::DoNotParticipate);
    }

    /**
     * Deadline inside the horizon and not yet submitted — §4's "Tenders Closing
     * Soon", and the reason somebody works late.
     *
     * @param  Builder<self>  $query
     */
    public function scopeClosingSoon(Builder $query, ?int $days = null): void
    {
        $days ??= (int) config('portal.tender_closing_soon_days', 7);

        $query->whereNotNull('submission_deadline_at')
            ->whereBetween('submission_deadline_at', [now(), now()->addDays($days)->endOfDay()])
            ->whereNotIn('stage', array_column(self::preSubmissionStagesExcluded(), 'value'));
    }

    /**
     * Deadline gone while the bid was still being prepared.
     *
     * @param  Builder<self>  $query
     */
    public function scopeDeadlinePassed(Builder $query): void
    {
        $query->whereNotNull('submission_deadline_at')
            ->where('submission_deadline_at', '<', now())
            ->whereNotIn('stage', array_column(self::preSubmissionStagesExcluded(), 'value'));
    }

    /** @param  Builder<self>  $query */
    public function scopeForOwner(Builder $query, ?int $employeeId): void
    {
        $query->when($employeeId, fn (Builder $builder) => $builder->where(function (Builder $inner) use ($employeeId): void {
            $inner->where('assigned_employee_id', $employeeId)
                ->orWhere('technical_owner_id', $employeeId)
                ->orWhere('sales_owner_id', $employeeId);
        }));
    }

    /**
     * A deadline only matters while the bid is still in our hands.
     */
    public function isDeadlinePassed(): bool
    {
        return $this->submission_deadline_at !== null
            && ! $this->stage->isSubmittedOrLater()
            && $this->submission_deadline_at->isPast();
    }

    public function isClosingSoon(?int $days = null): bool
    {
        $days ??= (int) config('portal.tender_closing_soon_days', 7);

        return $this->submission_deadline_at !== null
            && ! $this->stage->isSubmittedOrLater()
            && $this->submission_deadline_at->isFuture()
            && $this->submission_deadline_at->lte(now()->addDays($days));
    }

    /**
     * Time left before submission closes, e.g. "2d 4h" — null once the deadline
     * has gone or the bid has been submitted.
     */
    public function countdown(): ?string
    {
        if ($this->submission_deadline_at === null || $this->stage->isSubmittedOrLater() || $this->submission_deadline_at->isPast()) {
            return null;
        }

        return now()->diffForHumans($this->submission_deadline_at, ['parts' => 2, 'short' => true, 'syntax' => true]);
    }

    /**
     * Sequential, human-quotable tender code (TND-000042).
     */
    public static function nextCode(): string
    {
        $latest = self::withTrashed()->max('id') ?? 0;

        do {
            $latest++;
            $code = 'TND-'.Str::padLeft((string) $latest, 6, '0');
        } while (self::withTrashed()->where('code', $code)->exists());

        return $code;
    }

    /**
     * Stages past the point where a submission deadline still applies.
     *
     * @return array<int, TenderStage>
     */
    private static function preSubmissionStagesExcluded(): array
    {
        return array_filter(
            TenderStage::cases(),
            fn (TenderStage $stage): bool => $stage->isSubmittedOrLater(),
        );
    }
}
