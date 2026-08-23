<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\EligibilityStatus;
use App\Enums\Portal\TenderDecision;
use App\Enums\Portal\TenderPortalSource;
use App\Enums\Portal\TenderStage;
use App\Http\Controllers\Admin\Portal\Concerns\FiltersLists;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreTenderRequest;
use App\Http\Requests\Admin\Portal\UpdateTenderRequest;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Services\Portal\TenderCompletionService;
use App\Services\Portal\TenderTemplateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * The tender master record and the overview tab of its workspace (§7, §27).
 */
class TenderController extends Controller
{
    use FiltersLists, ResolvesTenderWorkspace;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Tender::class);

        $user = $request->user();
        $query = Tender::query()->with(['owner:id,name']);

        // Commercial detail stays with the people running the bid: an employee
        // sees the tenders they own or carry a task on, nothing else.
        if (! $user->portalRole()?->managesOthers()) {
            $employeeId = $user->employee?->id;
            $query->where(function ($builder) use ($employeeId): void {
                $builder->forOwner($employeeId)
                    ->orWhereHas('tasks', fn ($tasks) => $tasks->where('assigned_to_id', $employeeId));
            });
        }

        $this->filterSearch($query, $request, ['code', 'tender_number', 'title', 'customer_organization']);
        $this->filterEquals($query, $request, 'stage');
        $this->filterEquals($query, $request, 'decision');
        $this->filterEquals($query, $request, 'portal');
        $this->filterEquals($query, $request, 'owner', 'assigned_employee_id');
        $this->filterDateRange($query, $request, 'submission_deadline_at', 'deadline');

        if ($request->boolean('closing_soon')) {
            $query->closingSoon();
        }

        if ($request->boolean('active')) {
            $query->active();
        }

        $this->applySort($query, $request, ['submission_deadline_at', 'created_at', 'estimated_value', 'stage', 'title'], 'submission_deadline_at', 'asc');

        return view('admin.portal.tenders.index', [
            'tenders' => $query->paginate((int) config('portal.per_page', 20))->withQueryString(),
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'stages' => TenderStage::options(),
            'decisions' => TenderDecision::options(),
            'sources' => TenderPortalSource::options(),
            'filters' => $this->filterState($request),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Tender::class);

        return view('admin.portal.tenders.create', $this->formData());
    }

    /**
     * Creating a tender also builds its workspace: eligibility checklist,
     * document set, submission checks and the standard task list (§9, §10,
     * §12, §14). Nobody starts a bid from a blank page.
     */
    public function store(StoreTenderRequest $request, TenderTemplateService $templates): RedirectResponse
    {
        $data = $request->validated();
        $withTasks = $request->boolean('apply_task_template', true);
        unset($data['apply_task_template']);

        $tender = Tender::create([...$data, 'code' => Tender::nextCode()]);

        $templates->apply($tender, $request->user()->portalEmployee(), $withTasks);

        return redirect()
            ->route('admin.portal.tenders.show', $tender)
            ->with('status', "Tender {$tender->code} created with its standard checklists.");
    }

    public function show(Tender $tender): View
    {
        return view('admin.portal.tenders.tabs.overview', $this->viewWorkspace($tender, 'overview', [
            'upcomingTasks' => $tender->tasks()->open()->with('assignee:id,name')->orderBy('due_date')->limit(8)->get(),
            'dueOem' => $tender->oemFollowups()->open()->orderBy('next_followup_at')->limit(5)->get(),
            'missingDocuments' => $tender->documents()->missingMandatory()->orderBy('required_by')->limit(8)->get(),
        ]));
    }

    public function edit(Tender $tender): View
    {
        $this->authorize('update', $tender);

        return view('admin.portal.tenders.edit', [...$this->formData(), 'tender' => $tender]);
    }

    public function update(UpdateTenderRequest $request, Tender $tender, TenderCompletionService $completion): RedirectResponse
    {
        $tender->update($request->validated());

        $completion->refresh($tender);

        return redirect()
            ->route('admin.portal.tenders.show', $tender)
            ->with('status', "Tender {$tender->code} updated.");
    }

    public function destroy(Tender $tender): RedirectResponse
    {
        $this->authorize('delete', $tender);

        $tender->delete();

        return redirect()
            ->route('admin.portal.tenders.index')
            ->with('status', "Tender {$tender->code} deleted.");
    }

    /**
     * Shared data for the create and edit forms.
     *
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'stages' => TenderStage::options(),
            'decisions' => TenderDecision::options(),
            'sources' => TenderPortalSource::options(),
            'eligibilityStatuses' => EligibilityStatus::options(),
        ];
    }
}
