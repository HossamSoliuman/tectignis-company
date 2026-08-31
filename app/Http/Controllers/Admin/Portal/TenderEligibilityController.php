<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\ChecklistItemStatus;
use App\Enums\Portal\EligibilityStatus;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\OverrideEligibilityRequest;
use App\Http\Requests\Admin\Portal\StoreChecklistItemRequest;
use App\Http\Requests\Admin\Portal\UpdateChecklistItemRequest;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderChecklistItem;
use App\Services\Portal\EligibilityEvaluator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * The eligibility tab (§9): the requirement checklist and the verdict derived
 * from it.
 *
 * Nobody types the verdict — it falls out of the answers. The only way to
 * disagree with it is an explicit override that records a reason.
 */
class TenderEligibilityController extends Controller
{
    use ResolvesTenderWorkspace;

    public function index(Tender $tender, EligibilityEvaluator $evaluator): View
    {
        return view('admin.portal.tenders.tabs.eligibility', $this->viewWorkspace($tender, 'eligibility', [
            'items' => $tender->checklistItems()->with('responsible:id,name')->get(),
            'counts' => $evaluator->counts($tender),
            'derived' => $evaluator->evaluate($tender),
            'isOverridden' => $evaluator->isOverridden($tender),
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'itemStatuses' => ChecklistItemStatus::options(),
            'eligibilityStatuses' => EligibilityStatus::options(),
        ]));
    }

    public function store(StoreChecklistItemRequest $request, Tender $tender): RedirectResponse
    {
        $tender->checklistItems()->create([
            ...$request->validated(),
            'status' => ChecklistItemStatus::Pending,
            'sort_order' => (int) $tender->checklistItems()->max('sort_order') + 1,
        ]);

        return $this->back($tender, 'Requirement added to the eligibility checklist.');
    }

    public function update(UpdateChecklistItemRequest $request, Tender $tender, TenderChecklistItem $checklist_item): RedirectResponse
    {
        $checklist_item->update($request->validated());

        return $this->back($tender, 'Requirement updated.');
    }

    public function destroy(Tender $tender, TenderChecklistItem $checklist_item): RedirectResponse
    {
        $this->authorize('update', $tender);

        $checklist_item->delete();

        return $this->back($tender, 'Requirement removed.');
    }

    /**
     * Management decides to bid despite the checklist — always with a reason on
     * the record, never silently.
     */
    public function override(OverrideEligibilityRequest $request, Tender $tender, EligibilityEvaluator $evaluator): RedirectResponse
    {
        $data = $request->validated();

        $evaluator->override(
            $tender,
            EligibilityStatus::from($data['eligibility_status']),
            $data['eligibility_override_reason'],
        );

        return $this->back($tender, 'Eligibility overridden — the reason is on the record.');
    }

    /**
     * Drop the override and let the checklist speak for itself again.
     */
    public function clearOverride(Tender $tender, EligibilityEvaluator $evaluator): RedirectResponse
    {
        $this->authorize('overrideEligibility', $tender);

        $evaluator->clearOverride($tender);

        return $this->back($tender, 'Override cleared — eligibility follows the checklist again.');
    }

    private function back(Tender $tender, string $message): RedirectResponse
    {
        return redirect()
            ->route('admin.portal.tenders.eligibility.index', $tender)
            ->with('status', $message);
    }
}
