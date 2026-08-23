<?php

namespace App\Models\Portal;

use App\Enums\Portal\EmployeeStatus;
use App\Models\User;
use App\Observers\Portal\ActivityLogObserver;
use App\Policies\Portal\EmployeePolicy;
use Database\Factories\Portal\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[ObservedBy(ActivityLogObserver::class)]
#[UsePolicy(EmployeePolicy::class)]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    protected $table = 'portal_employees';

    protected $fillable = [
        'user_id',
        'employee_code',
        'name',
        'email',
        'phone',
        'department_id',
        'designation',
        'date_of_joining',
        'status',
        'reports_to_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_joining' => 'date',
            'status' => EmployeeStatus::class,
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reports_to_id');
    }

    /** @return HasMany<Employee, $this> */
    public function directReports(): HasMany
    {
        return $this->hasMany(self::class, 'reports_to_id');
    }

    /** @return HasMany<Task, $this> */
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    /** @return HasMany<DailyWorkUpdate, $this> */
    public function dailyWorkUpdates(): HasMany
    {
        return $this->hasMany(DailyWorkUpdate::class, 'employee_id');
    }

    /** @param  Builder<self>  $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', EmployeeStatus::Active);
    }

    /**
     * The employee record for a portal user, created on first use.
     *
     * Every portal actor must resolve to an employee — tasks are assigned to
     * employees and daily updates are logged by them — but a director who was
     * given portal access straight from the CMS user list may not have one yet.
     */
    public static function forUser(User $user): self
    {
        $employee = self::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_code' => self::nextCode(),
                'name' => $user->name,
                'email' => $user->email,
                'status' => EmployeeStatus::Active,
            ],
        );

        return $employee;
    }

    /**
     * Sequential, human-quotable employee code (EMP-0007).
     */
    public static function nextCode(): string
    {
        $latest = self::withoutGlobalScopes()->max('id') ?? 0;

        do {
            $latest++;
            $code = 'EMP-'.Str::padLeft((string) $latest, 4, '0');
        } while (self::where('employee_code', $code)->exists());

        return $code;
    }
}
