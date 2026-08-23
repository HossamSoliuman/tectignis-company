<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\DailyWorkCategory;
use App\Enums\Portal\DailyWorkStatus;
use App\Http\Controllers\Admin\Portal\Concerns\FiltersLists;
use App\Http\Controllers\Admin\Portal\Concerns\StoresPrivateFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreDailyWorkUpdateRequest;
use App\Http\Requests\Admin\Portal\UpdateDailyWorkUpdateRequest;
use App\Models\Portal\DailyWorkUpdate;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * The daily work log (§5) — the screen every employee touches once a day, so
 * the submit form is deliberately short and mobile-first.
 */
class DailyWorkUpdateController extends Controller
{
    use FiltersLists, StoresPrivateFiles;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', DailyWorkUpdate::class);

        $user = $request->user();
        $query = DailyWorkUpdate::query()->with(['employee:id,name,department_id', 'employee.department:id,name']);

        // Employees see their own day; managers and directors see the team's.
        if (! $user->portalRole()?->managesOthers()) {
            $query->where('employee_id', $user->employee?->id);
        }

        $this->filterSearch($query, $request, ['activity', 'remarks']);
        $this->filterEquals($query, $request, 'employee', 'employee_id');
        $this->filterEquals($query, $request, 'category', 'related_category');
        $this->filterEquals($query, $request, 'status');
        $this->filterDateRange($query, $request, 'work_date', 'date');

        $this->applySort($query, $request, ['work_date', 'created_at'], 'work_date');

        return view('admin.portal.daily-work.index', [
            'updates' => $query->paginate((int) config('portal.per_page', 20))->withQueryString(),
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'categories' => DailyWorkCategory::options(),
            'statuses' => DailyWorkStatus::options(),
            'filters' => $this->filterState($request),
            'canSeeTeam' => (bool) $user->portalRole()?->managesOthers(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', DailyWorkUpdate::class);

        return view('admin.portal.daily-work.create', $this->formData($request));
    }

    public function store(StoreDailyWorkUpdateRequest $request): RedirectResponse
    {
        $update = DailyWorkUpdate::create($this->attributes($request, $request->validated()));

        $this->storeOptionalUpload($update, 'daily-work/'.$update->id);

        return redirect()
            ->route('admin.portal.daily-work.index')
            ->with('status', 'Daily work update submitted.');
    }

    public function edit(Request $request, DailyWorkUpdate $dailyWork): View
    {
        $this->authorize('update', $dailyWork);

        return view('admin.portal.daily-work.edit', [...$this->formData($request), 'update' => $dailyWork]);
    }

    public function update(UpdateDailyWorkUpdateRequest $request, DailyWorkUpdate $dailyWork): RedirectResponse
    {
        $dailyWork->update($this->attributes($request, $request->validated(), $dailyWork));

        $this->storeOptionalUpload($dailyWork, 'daily-work/'.$dailyWork->id);

        return redirect()
            ->route('admin.portal.daily-work.index')
            ->with('status', 'Daily work update saved.');
    }

    public function destroy(DailyWorkUpdate $dailyWork): RedirectResponse
    {
        $this->authorize('delete', $dailyWork);

        $dailyWork->delete();

        return redirect()
            ->route('admin.portal.daily-work.index')
            ->with('status', 'Daily work update deleted.');
    }

    /**
     * Map the form payload onto table columns, resolving whose day this is and
     * turning the task picker into the polymorphic `related` link.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function attributes(Request $request, array $data, ?DailyWorkUpdate $existing = null): array
    {
        $taskId = $data['related_task_id'] ?? null;

        unset($data['attachment'], $data['related_task_id'], $data['employee_id']);

        return [
            ...$data,
            'employee_id' => $this->resolveEmployee($request, $existing),
            'related_type' => $taskId ? (new Task)->getMorphClass() : null,
            'related_id' => $taskId ?: null,
        ];
    }

    /**
     * Only management logs work on somebody else's behalf; everyone else logs
     * their own day regardless of what the form posted.
     */
    private function resolveEmployee(Request $request, ?DailyWorkUpdate $existing): int
    {
        if ($request->user()->portalRole()?->managesOthers()) {
            return $request->integer('employee_id')
                ?: $existing?->employee_id
                ?: $request->user()->portalEmployee()->id;
        }

        return $existing?->employee_id ?? $request->user()->portalEmployee()->id;
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Request $request): array
    {
        $user = $request->user();
        $employee = $user->portalEmployee();

        return [
            'employee' => $employee,
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'categories' => DailyWorkCategory::options(),
            'statuses' => DailyWorkStatus::options(),
            'canLogForOthers' => (bool) $user->portalRole()?->managesOthers(),
            'openTasks' => Task::query()
                ->open()
                ->where('assigned_to_id', $employee->id)
                ->orderBy('due_date')
                ->get(['id', 'code', 'title']),
        ];
    }
}
