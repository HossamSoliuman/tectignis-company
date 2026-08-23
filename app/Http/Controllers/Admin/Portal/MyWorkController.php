<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\Portal\DailyWorkUpdate;
use App\Models\Portal\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * The employee's own plate, grouped Overdue / Today / Upcoming.
 *
 * Designed mobile-first: this and the daily update form are the two screens
 * staff open on a phone between site visits (§23, §35).
 */
class MyWorkController extends Controller
{
    public function __invoke(Request $request): View
    {
        $employee = $request->user()->portalEmployee();

        $tasks = Task::query()
            ->open()
            ->where('assigned_to_id', $employee->id)
            ->with('related')
            ->orderByRaw('due_date is null')
            ->orderBy('due_date')
            ->get();

        return view('admin.portal.my-work', [
            'employee' => $employee,
            'overdue' => $tasks->filter(fn (Task $task): bool => $task->isOverdue())->values(),
            'today' => $this->dueToday($tasks),
            'upcoming' => $this->upcoming($tasks),
            'undated' => $tasks->whereNull('due_date')->values(),
            'loggedToday' => DailyWorkUpdate::query()
                ->where('employee_id', $employee->id)
                ->onDate(now())
                ->latest('created_at')
                ->get(),
        ]);
    }

    /**
     * @param  Collection<int, Task>  $tasks
     * @return Collection<int, Task>
     */
    private function dueToday(Collection $tasks): Collection
    {
        return $tasks
            ->filter(fn (Task $task): bool => $task->due_date !== null
                && ! $task->isOverdue()
                && $task->due_date->isToday())
            ->values();
    }

    /**
     * @param  Collection<int, Task>  $tasks
     * @return Collection<int, Task>
     */
    private function upcoming(Collection $tasks): Collection
    {
        return $tasks
            ->filter(fn (Task $task): bool => $task->due_date !== null
                && $task->due_date->isAfter(now()->endOfDay()))
            ->values();
    }
}
