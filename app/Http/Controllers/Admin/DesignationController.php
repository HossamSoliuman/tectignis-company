<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDesignationRequest;
use App\Http\Requests\Admin\UpdateDesignationRequest;
use App\Models\Designation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Designation master behind the careers form dropdown (spec §28.7–28.8).
 */
class DesignationController extends Controller
{
    use PaginatesTables;

    /**
     * Columns the list may be sorted by.
     *
     * @var list<string>
     */
    public const SORTABLE = ['sort_order', 'name', 'is_active'];

    public function index(Request $request): View
    {
        $sort = in_array($request->query('sort'), self::SORTABLE, true) ? $request->query('sort') : 'sort_order';
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $status = in_array($request->query('status'), ['active', 'inactive'], true) ? $request->query('status') : null;

        $query = Designation::query()
            ->select(['id', 'name', 'department', 'sort_order', 'is_active', 'updated_by', 'updated_at'])
            ->with('updater:id,name')
            ->withCount('applications')
            ->when($status, fn ($query) => $query->where('is_active', $status === 'active'))
            ->orderBy($sort, $direction)
            ->orderBy('name');

        return view('admin.designations.index', [
            'designations' => $this->paginateTable($request, $query, ['name', 'department']),
            'activeStatus' => $status,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function create(): View
    {
        return view('admin.designations.create', [
            'designation' => new Designation(['sort_order' => (int) Designation::max('sort_order') + 1]),
        ]);
    }

    public function store(StoreDesignationRequest $request): RedirectResponse
    {
        Designation::create([
            ...$request->validated(),
            'sort_order' => (int) $request->validated('sort_order'),
            'is_active' => $request->boolean('is_active'),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.designations.index')->with('status', 'Designation created.');
    }

    public function edit(Designation $designation): View
    {
        $designation->load(['creator:id,name', 'updater:id,name'])->loadCount('applications');

        return view('admin.designations.edit', compact('designation'));
    }

    public function update(UpdateDesignationRequest $request, Designation $designation): RedirectResponse
    {
        $designation->update([
            ...$request->validated(),
            'sort_order' => (int) $request->validated('sort_order'),
            'is_active' => $request->boolean('is_active'),
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.designations.index')->with('status', 'Designation updated.');
    }

    /**
     * Activate/deactivate in one click. Inactive designations leave the public
     * dropdown but stay on existing applications (spec §28.8).
     */
    public function toggle(Request $request, Designation $designation): RedirectResponse
    {
        $designation->update([
            'is_active' => ! $designation->is_active,
            'updated_by' => $request->user()->id,
        ]);

        return back()->with('status', $designation->is_active ? 'Designation activated.' : 'Designation deactivated.');
    }

    /**
     * Only designations no candidate has applied under may be deleted.
     */
    public function destroy(Designation $designation): RedirectResponse
    {
        if ($designation->applications()->exists()) {
            return back()->withErrors(['designation' => 'This designation is used by existing applications, so it cannot be deleted. Deactivate it instead.']);
        }

        $designation->delete();

        return redirect()->route('admin.designations.index')->with('status', 'Designation deleted.');
    }
}
