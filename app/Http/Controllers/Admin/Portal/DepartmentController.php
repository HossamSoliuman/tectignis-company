<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreDepartmentRequest;
use App\Http\Requests\Admin\Portal\UpdateDepartmentRequest;
use App\Models\Portal\Department;
use App\Models\Portal\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Department::class);

        return view('admin.portal.departments.index', [
            'departments' => Department::ordered()
                ->with('head:id,name')
                ->withCount('employees')
                ->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Department::class);

        return view('admin.portal.departments.create', $this->formData());
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());

        return redirect()
            ->route('admin.portal.departments.index')
            ->with('status', 'Department created.');
    }

    public function edit(Department $department): View
    {
        $this->authorize('update', $department);

        return view('admin.portal.departments.edit', [...$this->formData(), 'department' => $department]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()
            ->route('admin.portal.departments.index')
            ->with('status', 'Department updated.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->authorize('delete', $department);

        $department->delete();

        return redirect()
            ->route('admin.portal.departments.index')
            ->with('status', 'Department deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
        ];
    }
}
