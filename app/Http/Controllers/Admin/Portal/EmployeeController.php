<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\EmployeeStatus;
use App\Enums\Portal\PortalRole;
use App\Http\Controllers\Admin\Portal\Concerns\FiltersLists;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreEmployeeRequest;
use App\Http\Requests\Admin\Portal\UpdateEmployeeRequest;
use App\Models\Portal\Department;
use App\Models\Portal\Employee;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use FiltersLists;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Employee::class);

        $query = Employee::query()->with(['department:id,name', 'manager:id,name', 'user:id,portal_role']);

        $this->filterSearch($query, $request, ['name', 'employee_code', 'email', 'designation']);
        $this->filterEquals($query, $request, 'department', 'department_id');
        $this->filterEquals($query, $request, 'status');
        $this->applySort($query, $request, ['name', 'employee_code', 'date_of_joining', 'created_at'], 'name', 'asc');

        return view('admin.portal.employees.index', [
            'employees' => $query->paginate((int) config('portal.per_page', 20))->withQueryString(),
            'departments' => Department::ordered()->get(['id', 'name']),
            'statuses' => EmployeeStatus::options(),
            'filters' => $this->filterState($request),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Employee::class);

        return view('admin.portal.employees.create', $this->formData($request));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $portalRole = $data['portal_role'] ?? null;
        unset($data['portal_role']);

        $employee = Employee::create([
            ...$data,
            'employee_code' => ($data['employee_code'] ?? null) ?: Employee::nextCode(),
        ]);

        $this->syncPortalAccess($request, $employee, $portalRole);

        return redirect()
            ->route('admin.portal.employees.index')
            ->with('status', "Employee {$employee->name} created.");
    }

    public function show(Employee $employee): View
    {
        $this->authorize('view', $employee);

        $employee->load(['department:id,name', 'manager:id,name', 'directReports:id,name,designation', 'user:id,name,email,portal_role']);

        return view('admin.portal.employees.show', [
            'employee' => $employee,
            'openTasks' => $employee->assignedTasks()->open()->orderBy('due_date')->limit(10)->get(),
            'recentWork' => $employee->dailyWorkUpdates()->latest('work_date')->limit(10)->get(),
        ]);
    }

    public function edit(Request $request, Employee $employee): View
    {
        $this->authorize('update', $employee);

        return view('admin.portal.employees.edit', [...$this->formData($request), 'employee' => $employee]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();
        $portalRole = $data['portal_role'] ?? null;
        unset($data['portal_role']);

        $employee->update([
            ...$data,
            'employee_code' => ($data['employee_code'] ?? null) ?: $employee->employee_code,
        ]);

        $this->syncPortalAccess($request, $employee, $portalRole);

        return redirect()
            ->route('admin.portal.employees.index')
            ->with('status', "Employee {$employee->name} updated.");
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->authorize('delete', $employee);

        $employee->delete();

        return redirect()
            ->route('admin.portal.employees.index')
            ->with('status', 'Employee removed.');
    }

    /**
     * Grant or revoke portal access on the linked login. Director-only: this is
     * the one field on the employee form that changes what somebody can do.
     */
    private function syncPortalAccess(Request $request, Employee $employee, ?string $portalRole): void
    {
        if (! $request->user()->can('manageAccess', Employee::class) || $employee->user_id === null) {
            return;
        }

        $employee->user?->update(['portal_role' => $portalRole ?: null]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Request $request): array
    {
        return [
            'departments' => Department::ordered()->get(['id', 'name']),
            'managers' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'statuses' => EmployeeStatus::options(),
            'portalRoles' => PortalRole::options(),
            'canManageAccess' => $request->user()->can('manageAccess', Employee::class),
            'linkableUsers' => User::query()->orderBy('name')->get(['id', 'name', 'email']),
        ];
    }
}
