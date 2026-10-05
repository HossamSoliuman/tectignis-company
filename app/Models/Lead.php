<?php

namespace App\Models;

use App\Enums\LeadBudget;
use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Enums\LeadTimeline;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Lead extends Model
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory, SoftDeletes;

    /**
     * UTM parameters captured from the visitor's landing URL.
     *
     * @var list<string>
     */
    public const UTM_FIELDS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

    protected $fillable = [
        'name', 'email', 'company', 'country', 'service', 'phone', 'subject', 'message', 'budget', 'timeline',
        'attachment', 'source', 'page_url', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'status', 'assigned_to', 'is_read', 'consented_at',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'new',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'status' => LeadStatus::class,
            'consented_at' => 'datetime',
        ];
    }

    /**
     * The sales/admin user responsible for following up.
     *
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return HasMany<LeadNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest('id');
    }

    /**
     * @return HasMany<LeadActivity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest('id');
    }

    /**
     * Human-friendly lead ID, e.g. "L-00042".
     */
    public function reference(): string
    {
        return 'L-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function sourceEnum(): ?LeadSource
    {
        return LeadSource::tryFrom((string) $this->source);
    }

    public function sourceLabel(): string
    {
        return $this->sourceEnum()?->label() ?? ucfirst((string) ($this->source ?: 'contact'));
    }

    public function budgetLabel(): ?string
    {
        return LeadBudget::tryFrom((string) $this->budget)?->label() ?? $this->budget;
    }

    public function timelineLabel(): ?string
    {
        return LeadTimeline::tryFrom((string) $this->timeline)?->label() ?? $this->timeline;
    }

    /**
     * Whether any landing-page or campaign attribution was captured.
     */
    public function hasAttribution(): bool
    {
        return filled($this->page_url) || collect(self::UTM_FIELDS)->contains(fn (string $field): bool => filled($this->{$field}));
    }

    /**
     * Append an entry to the audit trail.
     */
    public function recordActivity(string $type, ?User $user = null, ?string $oldValue = null, ?string $newValue = null): LeadActivity
    {
        return $this->activities()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

    /**
     * Apply the Leads admin search and filters (spec §26.6). Every key is
     * optional; values are expected to be validated already.
     *
     * @param  Builder<Lead>  $query
     * @param  array{q?: string|null, status?: string|null, service?: string|null, country?: string|null, assigned_to?: string|int|null, source?: string|null, date_from?: string|null, date_to?: string|null}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['q'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $like = '%'.$search.'%';

                $query->where('name', 'like', $like)
                    ->orWhere('company', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like);

                // "L-00042", "00042" and "42" all find lead #42.
                $id = ltrim(preg_replace('/^L-/i', '', $search), '0');
                if ($id !== '' && ctype_digit($id)) {
                    $query->orWhere('id', (int) $id);
                }
            });
        }

        foreach (['status', 'service', 'country', 'source'] as $column) {
            if (filled($filters[$column] ?? null)) {
                $query->where($column, $filters[$column]);
            }
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
