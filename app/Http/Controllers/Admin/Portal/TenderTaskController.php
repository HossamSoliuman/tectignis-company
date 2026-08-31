<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\TaskPriority;
use App\Enums\Portal\TaskStatus;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreTenderTaskRequest;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\Portal\Tender;
use App\Services\Portal\TenderTemplateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * The tasks tab of a tender workspace (§12).
 *
 * These are ordinary portal tasks attached through the `related` morph, so they
 * appear in My Work and go overdue through the same engine as everything else —
 * tender work is not a second, parallel to-do list.
 */
class TenderTaskController extends Controller
{
    use ResolvesTenderWorkspace;

    public function index(Tender $tender): View
    {
        return view('admin.portal.tenders.tabs.tasks', $this->viewWorkspace($tender, 'tasks', [
            'tasks' => $tender->tasks()
                ->with(['assignee:id,name', 'creator:id,name'])
                ->orderByRaw('due_date is null, due_date')
                ->get(),
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'priorities' => TaskPriority::options(),
            'statuses' => TaskStatus::options(),
        ]));
    }

    public function store(StoreTenderTaskRequest $request, Tender $tender): RedirectResponse
    {
        $employee = $request->user()->portalEmployee();

        $tender->tasks()->create([
            ...$request->validated(),
            'code' => Task::nextCode(),
            'created_by_id' => $employee->id,
            'status' => TaskStatus::NotStarted,
        ]);

        return redirect()
            ->route('admin.portal.tenders.tasks.index', $tender)
            ->with('status', 'Task added to this tender.');
    }

    /**
     * Re-apply the §12 step template — for a tender created before the
     * template existed, or one whose task list was cleared by mistake.
     */
    public function applyTemplate(Tender $tender, TenderTemplateService $templates): RedirectResponse
    {
        $this->authorize('update', $tender);

        $created = $templates->applyTaskTemplate($tender, $tender->owner);

        return redirect()
            ->route('admin.portal.tenders.tasks.index', $tender)
            ->with('status', $created > 0
                ? "{$created} standard tender tasks created."
                : 'The standard tasks are already on this tender.');
    }
}
