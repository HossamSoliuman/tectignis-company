<?php

namespace App\Services\Portal;

use App\Enums\Portal\TaskStatus;
use App\Models\Portal\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * The single source of truth for "is this late?".
 *
 * Overdue is always *derived* from the deadline — never trusted from a stored
 * flag — so a screen can never disagree with a report. The cached
 * `is_overdue` / `overdue_at` columns exist only so alerts and cross-table
 * queries stay cheap; `refresh()` reconciles them, and nothing reads them to
 * decide the truth.
 */
class OverdueService
{
    /**
     * Reconcile the cached overdue columns with reality.
     *
     * @return array{flagged: int, cleared: int}
     */
    public function refresh(?Carbon $asOf = null): array
    {
        $asOf ??= now();
        $closed = array_column(TaskStatus::closed(), 'value');

        $flagged = Task::query()
            ->whereNotNull('due_date')
            ->where('due_date', '<', $asOf)
            ->whereNotIn('status', $closed)
            ->where('is_overdue', false)
            ->update(['is_overdue' => true, 'overdue_at' => $asOf]);

        // A task becomes "not late" again when it is completed, cancelled, or
        // its deadline is pushed out — all three must clear the flag.
        $cleared = Task::query()
            ->where('is_overdue', true)
            ->where(function ($query) use ($asOf, $closed): void {
                $query->whereIn('status', $closed)
                    ->orWhereNull('due_date')
                    ->orWhere('due_date', '>=', $asOf);
            })
            ->update(['is_overdue' => false, 'overdue_at' => null]);

        return ['flagged' => $flagged, 'cleared' => $cleared];
    }

    /**
     * Open tasks whose deadline has already passed, most overdue first.
     *
     * @return Collection<int, Task>
     */
    public function overdueTasks(?int $employeeId = null, int $limit = 50): Collection
    {
        return Task::query()
            ->overdue()
            ->forEmployee($employeeId)
            ->with(['assignee:id,name', 'related'])
            ->orderBy('due_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Open tasks falling due inside the configured horizon.
     *
     * @return Collection<int, Task>
     */
    public function dueSoonTasks(?int $employeeId = null, ?int $days = null): Collection
    {
        $days ??= (int) config('portal.due_soon_days', 3);

        return Task::query()
            ->open()
            ->forEmployee($employeeId)
            ->dueBetween(now(), now()->addDays($days)->endOfDay())
            ->with(['assignee:id,name', 'related'])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Headline counts for the dashboard, scoped to one employee when given.
     *
     * @return array{total: int, open: int, overdue: int, due_today: int, completed: int}
     */
    public function counts(?int $employeeId = null): array
    {
        return [
            'total' => Task::query()->forEmployee($employeeId)->count(),
            'open' => Task::query()->open()->forEmployee($employeeId)->count(),
            'overdue' => Task::query()->overdue()->forEmployee($employeeId)->count(),
            'due_today' => Task::query()
                ->open()
                ->forEmployee($employeeId)
                ->dueBetween(now()->startOfDay(), now()->endOfDay())
                ->count(),
            'completed' => Task::query()
                ->forEmployee($employeeId)
                ->where('status', TaskStatus::Completed)
                ->count(),
        ];
    }
}
