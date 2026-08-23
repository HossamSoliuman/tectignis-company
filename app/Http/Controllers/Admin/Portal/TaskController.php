<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\TaskPriority;
use App\Enums\Portal\TaskStatus;
use App\Http\Controllers\Admin\Portal\Concerns\FiltersLists;
use App\Http\Controllers\Admin\Portal\Concerns\StoresPrivateFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreTaskRequest;
use App\Http\Requests\Admin\Portal\UpdateTaskRequest;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\Portal\TaskUpdate;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use FiltersLists, StoresPrivateFiles;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $user = $request->user();
        $query = Task::query()->with(['assignee:id,name', 'creator:id,name']);

        // Employees and viewers only ever see their own plate; the filter runs
        // in SQL rather than after the fact so pagination counts stay honest.
        if (! $user->portalRole()?->managesOthers()) {
            $employeeId = $user->employee?->id;
            $query->where(function (Builder $builder) use ($employeeId): void {
                $builder->where('assigned_to_id', $employeeId)
                    ->orWhere('created_by_id', $employeeId);
            });
        }

        $this->filterSearch($query, $request, ['code', 'title', 'description']);
        $this->filterEquals($query, $request, 'status');
        $this->filterEquals($query, $request, 'priority');
        $this->filterEquals($query, $request, 'employee', 'assigned_to_id');
        $this->filterDateRange($query, $request, 'due_date', 'due');

        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        $this->applySort($query, $request, ['due_date', 'created_at', 'priority', 'status', 'title'], 'due_date', 'asc');

        return view('admin.portal.tasks.index', [
            'tasks' => $query->paginate((int) config('portal.per_page', 20))->withQueryString(),
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'statuses' => TaskStatus::options(),
            'priorities' => TaskPriority::options(),
            'filters' => $this->filterState($request),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Task::class);

        return view('admin.portal.tasks.create', $this->formData($request));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $creator = $request->user()->portalEmployee();

        $task = Task::create([
            ...$this->withoutFileFields($data),
            'code' => Task::nextCode(),
            'assigned_to_id' => $this->resolveAssignee($request, $creator),
            'created_by_id' => $creator->id,
            'completed_at' => ($data['status'] ?? null) === TaskStatus::Completed->value ? now() : null,
        ]);

        $this->storeOptionalUpload($task, 'tasks/'.$task->id);

        return redirect()
            ->route('admin.portal.tasks.show', $task)
            ->with('status', "Task {$task->code} created.");
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load([
            'assignee:id,name',
            'creator:id,name',
            'updates.employee:id,name',
            'attachments.uploader:id,name',
            'comments.employee:id,name',
        ]);

        return view('admin.portal.tasks.show', [
            'task' => $task,
            'statuses' => TaskStatus::options(),
        ]);
    }

    public function edit(Request $request, Task $task): View
    {
        $this->authorize('update', $task);

        return view('admin.portal.tasks.edit', [...$this->formData($request), 'task' => $task]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $data = $request->validated();
        $statusBefore = $task->status;
        $statusAfter = TaskStatus::from($data['status']);

        $task->update([
            ...$this->withoutFileFields($data),
            'assigned_to_id' => $this->resolveAssignee($request, $task->assignee),
            'completed_at' => $statusAfter === TaskStatus::Completed ? ($task->completed_at ?? now()) : null,
            // Work that was signed off and then re-opened is a quality signal
            // the performance reports in Phase 4 read directly.
            'reopened_count' => $statusBefore === TaskStatus::Completed && $statusAfter !== TaskStatus::Completed
                ? $task->reopened_count + 1
                : $task->reopened_count,
        ]);

        if ($statusBefore !== $statusAfter) {
            TaskUpdate::create([
                'task_id' => $task->id,
                'employee_id' => $request->user()->portalEmployee()->id,
                'note' => 'Status changed from '.$statusBefore->label().' to '.$statusAfter->label().'.',
                'progress' => $task->progress,
                'status_from' => $statusBefore,
                'status_to' => $statusAfter,
                'logged_at' => now(),
            ]);
        }

        $this->storeOptionalUpload($task, 'tasks/'.$task->id);

        return redirect()
            ->route('admin.portal.tasks.show', $task)
            ->with('status', "Task {$task->code} updated.");
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('admin.portal.tasks.index')
            ->with('status', "Task {$task->code} deleted.");
    }

    /**
     * Shared data for the create and edit forms.
     *
     * @return array<string, mixed>
     */
    private function formData(Request $request): array
    {
        return [
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'statuses' => TaskStatus::options(),
            'priorities' => TaskPriority::options(),
            'canAssignOthers' => $request->user()->can('assignToOthers', Task::class),
        ];
    }

    /**
     * Employees may raise tasks, but the work lands on their own plate:
     * assigning to somebody else is a management action.
     */
    private function resolveAssignee(Request $request, ?Employee $fallback): ?int
    {
        if ($request->user()->can('assignToOthers', Task::class)) {
            return $request->integer('assigned_to_id') ?: null;
        }

        return $fallback?->id;
    }

    /**
     * Validated attributes minus the fields that are not table columns.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function withoutFileFields(array $data): array
    {
        unset($data['attachment'], $data['assigned_to_id']);

        return $data;
    }
}
