<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeadFilterRequest;
use App\Http\Requests\Admin\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/**
 * The Leads module (spec §26.6): a filterable pipeline of website enquiries,
 * kept completely separate from careers.
 */
class LeadController extends Controller
{
    use PaginatesTables;

    /**
     * Columns shown in the list — no SELECT * (spec §28.4).
     *
     * @var list<string>
     */
    private const LIST_COLUMNS = [
        'id', 'name', 'email', 'phone', 'company', 'country', 'service', 'source',
        'status', 'assigned_to', 'is_read', 'created_at', 'deleted_at',
    ];

    public function index(LeadFilterRequest $request): View
    {
        $filters = $request->filters();

        $leads = $this->baseQuery($request)
            ->select(self::LIST_COLUMNS)
            ->with('assignee:id,name')
            ->filter($filters)
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        // Pipeline counts honour every filter except the status itself, so the
        // tabs double as the "by period" dashboard metrics when a date range is set.
        $statusCounts = $this->baseQuery($request)
            ->filter(collect($filters)->except('status')->all())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.leads.index', [
            'leads' => $leads,
            'statusCounts' => $statusCounts,
            'filters' => $filters,
            'trashed' => $request->wantsTrashed(),
            'serviceOptions' => Lead::query()->whereNotNull('service')->distinct()->orderBy('service')->pluck('service'),
            'countryOptions' => Lead::query()->whereNotNull('country')->distinct()->orderBy('country')->pluck('country'),
            'assignees' => User::leadAssignees()->get(['id', 'name']),
            'sources' => LeadSource::options(),
            'statuses' => LeadStatus::cases(),
        ]);
    }

    public function show(Lead $lead): View
    {
        Gate::authorize('view', $lead);

        // Trashed leads stay reachable for Super Admins only, so they can restore them.
        abort_if($lead->trashed() && ! auth()->user()->isSuperAdmin(), 404);

        if (! $lead->is_read) {
            $lead->update(['is_read' => true]);
        }

        $lead->load(['assignee:id,name', 'notes.author:id,name', 'activities.user:id,name']);

        return view('admin.leads.show', [
            'lead' => $lead,
            'assignees' => User::leadAssignees()->get(['id', 'name']),
        ]);
    }

    /**
     * Change status and/or assignment, auditing each change.
     */
    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $user = $request->user();
        $status = LeadStatus::from($request->validated('status'));
        $assigneeId = $request->validated('assigned_to');
        $assigneeId = $assigneeId !== null ? (int) $assigneeId : null;

        if ($status !== $lead->status) {
            $lead->recordActivity(LeadActivity::STATUS_CHANGED, $user, $lead->status->value, $status->value);
        }

        if ($assigneeId !== $lead->assigned_to) {
            $previous = $lead->assignee?->name;
            $next = $assigneeId ? User::find($assigneeId)?->name : null;
            $lead->recordActivity(LeadActivity::ASSIGNED, $user, $previous, $next);
        }

        $lead->update(['status' => $status, 'assigned_to' => $assigneeId]);

        return back()->with('status', 'Lead updated.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        Gate::authorize('delete', $lead);

        $lead->recordActivity(LeadActivity::DELETED, auth()->user());
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead moved to trash.');
    }

    public function restore(Lead $lead): RedirectResponse
    {
        Gate::authorize('restore', $lead);

        $lead->restore();
        $lead->recordActivity(LeadActivity::RESTORED, auth()->user());

        return redirect()->route('admin.leads.show', $lead)->with('status', 'Lead restored.');
    }

    /**
     * Live leads, or only trashed ones for a Super Admin browsing the trash.
     *
     * @return Builder<Lead>
     */
    private function baseQuery(LeadFilterRequest $request): Builder
    {
        return $request->wantsTrashed() ? Lead::onlyTrashed() : Lead::query();
    }
}
