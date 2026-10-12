<?php

namespace App\Models;

use App\Enums\CandidateExperience;
use App\Enums\JobApplicationStatus;
use Database\Factories\JobApplicationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * A candidate's application from the careers page (spec §26.10). Lives in the
 * Careers module only and never in Leads (spec §27). The CV sits on the
 * private careers disk and is only reachable through the admin download route.
 */
class JobApplication extends Model
{
    /** @use HasFactory<JobApplicationFactory> */
    use HasFactory;

    /**
     * Private disk holding candidate CVs (spec §26.13).
     */
    public const RESUME_DISK = 'careers';

    /**
     * @var list<string>
     */
    public const NOTICE_PERIODS = ['Immediate', '15 days', '30 days', '60 days', '90 days'];

    protected $fillable = [
        'job_opening_id', 'job_title', 'designation_id', 'designation_name', 'name', 'email', 'phone', 'location',
        'experience', 'current_role', 'notice_period', 'linkedin_url', 'portfolio_url', 'cover_letter',
        'resume_path', 'resume_name', 'resume_mime', 'resume_size', 'status', 'assigned_to', 'is_read',
        'consented_at', 'archived_at',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'new',
        'is_read' => false,
    ];

    protected function casts(): array
    {
        return [
            'status' => JobApplicationStatus::class,
            'is_read' => 'boolean',
            'resume_size' => 'integer',
            'consented_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<JobOpening, $this>
     */
    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class);
    }

    /**
     * @return BelongsTo<Designation, $this>
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * The HR/recruiter responsible for this candidate.
     *
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return HasMany<JobApplicationNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(JobApplicationNote::class)->latest('id');
    }

    /**
     * @return HasMany<JobApplicationActivity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(JobApplicationActivity::class)->latest('id');
    }

    /**
     * Human-friendly application ID, e.g. "A-00042".
     */
    public function reference(): string
    {
        return 'A-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * The vacancy applied for, as it was named at the time of applying.
     */
    public function appliedFor(): string
    {
        return $this->job_title ?: 'General application';
    }

    public function experienceLabel(): ?string
    {
        return CandidateExperience::tryFrom((string) $this->experience)?->label() ?? $this->experience;
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    public function hasResume(): bool
    {
        return filled($this->resume_path);
    }

    /**
     * A predictable, safe file name for the downloaded CV, e.g.
     * "jane-doe-A-00042.pdf". The stored name on disk stays random.
     */
    public function resumeDownloadName(): string
    {
        $extension = Str::lower(pathinfo((string) $this->resume_path, PATHINFO_EXTENSION)) ?: 'pdf';

        return (Str::slug($this->name) ?: 'candidate').'-'.$this->reference().'.'.$extension;
    }

    /**
     * Append an entry to the audit trail.
     */
    public function recordActivity(string $type, ?User $user = null, ?string $oldValue = null, ?string $newValue = null): JobApplicationActivity
    {
        return $this->activities()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

    /**
     * @param  Builder<JobApplication>  $query
     */
    public function scopeArchived(Builder $query): void
    {
        $query->whereNotNull('archived_at');
    }

    /**
     * @param  Builder<JobApplication>  $query
     */
    public function scopeUnarchived(Builder $query): void
    {
        $query->whereNull('archived_at');
    }

    /**
     * Apply the Applications admin search and filters (spec §26.8). Every key
     * is optional; values are expected to be validated already.
     *
     * @param  Builder<JobApplication>  $query
     * @param  array{q?: string|null, status?: string|null, job_opening_id?: string|int|null, designation_id?: string|int|null, experience?: string|null, location?: string|null, assigned_to?: string|int|null, date_from?: string|null, date_to?: string|null}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['q'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $like = '%'.$search.'%';

                $query->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('job_title', 'like', $like)
                    ->orWhere('designation_name', 'like', $like);

                // "A-00042", "00042" and "42" all find application #42.
                $id = ltrim(preg_replace('/^A-/i', '', $search), '0');
                if ($id !== '' && ctype_digit($id)) {
                    $query->orWhere('id', (int) $id);
                }
            });
        }

        foreach (['status', 'experience', 'designation_id'] as $column) {
            if (filled($filters[$column] ?? null)) {
                $query->where($column, $filters[$column]);
            }
        }

        $job = $filters['job_opening_id'] ?? null;
        if ($job === 'general') {
            $query->whereNull('job_opening_id');
        } elseif (filled($job)) {
            $query->where('job_opening_id', (int) $job);
        }

        if (filled($filters['location'] ?? null)) {
            $query->where('location', 'like', '%'.trim($filters['location']).'%');
        }

        $assignee = $filters['assigned_to'] ?? null;
        if ($assignee === 'unassigned') {
            $query->whereNull('assigned_to');
        } elseif (filled($assignee)) {
            $query->where('assigned_to', (int) $assignee);
        }

        if (filled($filters['date_from'] ?? null)) {
            $query->where('created_at', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }

        if (filled($filters['date_to'] ?? null)) {
            $query->where('created_at', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }
    }
}
