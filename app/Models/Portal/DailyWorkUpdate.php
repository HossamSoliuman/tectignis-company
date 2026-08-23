<?php

namespace App\Models\Portal;

use App\Enums\Portal\DailyWorkCategory;
use App\Enums\Portal\DailyWorkStatus;
use App\Observers\Portal\ActivityLogObserver;
use App\Policies\Portal\DailyWorkUpdatePolicy;
use Database\Factories\Portal\DailyWorkUpdateFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

#[ObservedBy(ActivityLogObserver::class)]
#[UsePolicy(DailyWorkUpdatePolicy::class)]
class DailyWorkUpdate extends Model
{
    /** @use HasFactory<DailyWorkUpdateFactory> */
    use HasFactory;

    protected $table = 'portal_daily_work_updates';

    protected $fillable = [
        'employee_id',
        'work_date',
        'related_type',
        'related_id',
        'related_category',
        'activity',
        'start_time',
        'end_time',
        'progress',
        'status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'related_category' => DailyWorkCategory::class,
            'status' => DailyWorkStatus::class,
            'progress' => 'integer',
        ];
    }

    /** @return BelongsTo<Employee, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /** @return MorphTo<Model, $this> */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return MorphMany<Attachment, $this> */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /** @param  Builder<self>  $query */
    public function scopeOnDate(Builder $query, Carbon $date): void
    {
        $query->whereDate('work_date', $date);
    }

    /** @param  Builder<self>  $query */
    public function scopeForEmployee(Builder $query, ?int $employeeId): void
    {
        $query->when($employeeId, fn (Builder $builder) => $builder->where('employee_id', $employeeId));
    }

    /**
     * Duration in minutes when both ends of the activity were recorded.
     */
    public function durationMinutes(): ?int
    {
        if (! $this->start_time || ! $this->end_time) {
            return null;
        }

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $end->greaterThan($start) ? $start->diffInMinutes($end) : null;
    }
}
