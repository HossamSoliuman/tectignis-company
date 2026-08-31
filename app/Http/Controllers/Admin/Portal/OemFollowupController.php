<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\OemRequirementType;
use App\Http\Controllers\Admin\Portal\Concerns\FiltersLists;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreOemFollowupRequest;
use App\Http\Requests\Admin\Portal\UpdateOemFollowupRequest;
use App\Models\Portal\OemFollowup;
use App\Models\Portal\Tender;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * The cross-tender OEM chase queue (§11).
 *
 * A tender workspace shows what one bid is waiting on; this screen answers the
 * question that actually loses bids — "what has nobody chased today?" — across
 * every tender at once.
 */
class OemFollowupController extends Controller
{
    use FiltersLists;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', OemFollowup::class);

        $query = OemFollowup::query()->with(['tender:id,code,title', 'requester:id,name']);

        // An employee only sees chases on bids they are part of; standalone
        // items carry no commercial detail, so they stay visible to everyone.
        if (! $request->user()->portalRole()?->managesOthers()) {
            $visible = Tender::query()->forOwner($request->user()->employee?->id)->select('id');
            $query->where(fn ($builder) => $builder->whereNull('tender_id')->orWhereIn('tender_id', $visible));
        }

        $this->filterSearch($query, $request, ['oem_name', 'product', 'contact_person']);
        $this->filterEquals($query, $request, 'status');
        $this->filterEquals($query, $request, 'requirement_type');
        $this->filterEquals($query, $request, 'tender', 'tender_id');

        if ($request->boolean('due')) {
            $query->due();
        }

        if ($request->boolean('open')) {
            $query->open();
        }

        $this->applySort($query, $request, ['next_followup_at', 'required_by', 'oem_name', 'created_at'], 'next_followup_at', 'asc');

        return view('admin.portal.oem-followups.index', [
            'followups' => $query->paginate((int) config('portal.per_page', 20))->withQueryString(),
            'requirementTypes' => OemRequirementType::options(),
            'followupStatuses' => OemFollowupStatus::options(),
            'tenders' => $this->selectableTenders(),
            'filters' => $this->filterState($request),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', OemFollowup::class);

        return view('admin.portal.oem-followups.create', $this->formData());
    }

    public function store(StoreOemFollowupRequest $request): RedirectResponse
    {
        $followup = OemFollowup::create([
            ...$request->validated(),
            'requested_by_id' => $request->user()->portalEmployee()->id,
        ]);

        return redirect()
            ->route('admin.portal.oem-followups.show', $followup)
            ->with('status', "Follow-up with {$followup->oem_name} opened.");
    }

    public function show(OemFollowup $oem_followup): View
    {
        $this->authorize('view', $oem_followup);

        return view('admin.portal.oem-followups.show', [
            'followup' => $oem_followup->load([
                'tender:id,code,title',
                'requester:id,name',
                'attachments',
                'followupUpdates' => fn ($query) => $query->with(['employee:id,name', 'attachments'])->latest('id'),
            ]),
            'followupStatuses' => OemFollowupStatus::options(),
        ]);
    }

    public function edit(OemFollowup $oem_followup): View
    {
        $this->authorize('update', $oem_followup);

        return view('admin.portal.oem-followups.edit', [...$this->formData(), 'followup' => $oem_followup]);
    }

    public function update(UpdateOemFollowupRequest $request, OemFollowup $oem_followup): RedirectResponse
    {
        $oem_followup->update($request->validated());

        return redirect()
            ->route('admin.portal.oem-followups.show', $oem_followup)
            ->with('status', 'Follow-up updated.');
    }

    public function destroy(OemFollowup $oem_followup): RedirectResponse
    {
        $this->authorize('delete', $oem_followup);

        $oem_followup->delete();

        return redirect()
            ->route('admin.portal.oem-followups.index')
            ->with('status', 'Follow-up deleted.');
    }

    /** @return array<string, mixed> */
    private function formData(): array
    {
        return [
            'tenders' => $this->selectableTenders(),
            'requirementTypes' => OemRequirementType::options(),
            'followupStatuses' => OemFollowupStatus::options(),
        ];
    }

    /**
     * Tenders the current user may attach a chase to.
     *
     * @return Collection<int, Tender>
     */
    private function selectableTenders(): Collection
    {
        $user = request()->user();

        return Tender::query()
            ->active()
            ->when(
                ! $user->portalRole()?->managesOthers(),
                fn ($query) => $query->forOwner($user->employee?->id),
            )
            ->orderBy('submission_deadline_at')
            ->get(['id', 'code', 'title']);
    }
}
