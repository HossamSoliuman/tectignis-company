<?php

namespace App\Models\Portal;

use App\Enums\Portal\TaskPriority;
use App\Enums\Portal\TaskStatus;
use App\Observers\Portal\ActivityLogObserver;
use App\Observers\Portal\TenderProgressObserver;
use App\Policies\Portal\TaskPolicy;
use Database\Factories\Portal\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * A single unit of assignable work.
 *
 * One table serves standalone tasks and tasks belonging to another record
 * (tender, sales lead, customer) through the `related` morph, so the overdue
 * engine, notifications and "my work" list have exactly one implementation.
 */
#[ObservedBy([ActivityLogObserver::class, TenderProgressObserver::class])]
#[UsePolicy(TaskPolicy::class)]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'portal_tasks';

    protected $fillable = [
        'code',
        'title',
        'description',
        'assigned_to_id',
        'created_by_id',
        'priority',
        'status',
        'progress',
        'start_date',
        'due_date',
        'completed_at',
        'related_type',
        'related_id',
        'is_overdue',
        'overdue_at',
        'reopened_count',
    ];

    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
            'progress' => 'integer',
            'start_date' => 'datetime',
            'due_date' => 'datetime',
            'completed_at' => 'datetime',
            'is_overdue' => 'boolean',
            'overdue_at' => 'datetime',
            'reopened_count' => 'integer',
        ];
    }

    /** @return BelongsTo<Employee, $this> */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by_id');
    }

    /** @return MorphTo<Model, $this> */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return HasMany<TaskUpdate, $this> */
    public function updates(): HasMany
    {
        return $this->hasMany(TaskUpdate::class, 'task_id');
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
     * Late right now — computed live so lists, the dashboard and reports can
     * never disagree with each other because a flag was not refreshed.
     *
     * @param  Builder<self>  $query
     */
    public function scopeOverdue(Builder $query): void
    {
        $query->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNotIn('status', array_column(TaskStatus::closed(), 'value'));
    }

    /** @param  Builder<self>  $query */
    public function scopeOpen(Builder $query): void
    {
        $query->whereNotIn('status', array_column(TaskStatus::closed(), 'value'));
    }

    /** @param  Builder<self>  $query */
    public function scopeDueBetween(Builder $query, Carbon $from, Carbon $to): void
    {
        $query->whereNotNull('due_date')->whereBetween('due_date', [$from, $to]);
    }

    /** @param  Builder<self>  $query */
    public function scopeForEmployee(Builder $query, ?int $employeeId): void
    {
        $query->when($employeeId, fn (Builder $builder) => $builder->where('assigned_to_id', $employeeId));
    }

    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && ! $this->status->isClosed()
            && $this->due_date->isPast();
    }

    /**
     * Whole days late, or null when the task is not overdue.
     */
    public function daysOverdue(): ?int
    {
        return $this->isOverdue() ? $this->due_date->diffInDays(now()) : null;
    }

    /**
     * Sequential, human-quotable task code (TSK-000123).
     */
    public static function nextCode(): string
    {
        $latest = self::withTrashed()->max('id') ?? 0;

        do {
            $latest++;
            $code = 'TSK-'.Str::padLeft((string) $latest, 6, '0');
        } while (self::withTrashed()->where('code', $code)->exists());

        return $code;
    }
}
