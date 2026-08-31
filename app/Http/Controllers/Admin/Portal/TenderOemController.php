<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\OemFollowupStatus;
use App\Enums\Portal\OemRequirementType;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreOemFollowupRequest;
use App\Models\Portal\Tender;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * The OEM follow-up tab of a tender workspace (§11).
 *
 * The same records appear in the global chase queue served by
 * OemFollowupController; this tab is simply the tender's slice of it, so a bid
 * owner can see everything they are waiting on from manufacturers in one place.
 */
class TenderOemController extends Controller
{
    use ResolvesTenderWorkspace;

    public function index(Tender $tender): View
    {
        return view('admin.portal.tenders.tabs.oem', $this->viewWorkspace($tender, 'oem', [
            'followups' => $tender->oemFollowups()
                ->with(['requester:id,name', 'followupUpdates' => fn ($query) => $query->latest('id')->limit(3)])
                ->orderByRaw('next_followup_at is null, next_followup_at')
                ->get(),
            'requirementTypes' => OemRequirementType::options(),
            'followupStatuses' => OemFollowupStatus::options(),
        ]));
    }

    /**
     * Raise a new chase against this tender. The tender is taken from the URL,
     * not the form, so a follow-up cannot be filed against a bid the user
     * cannot see.
     */
    public function store(StoreOemFollowupRequest $request, Tender $tender): RedirectResponse
    {
        $this->authorize('update', $tender);

        $tender->oemFollowups()->create([
            ...$request->safe()->except('tender_id'),
            'requested_by_id' => $request->user()->portalEmployee()->id,
        ]);

        return redirect()
            ->route('admin.portal.tenders.oem.index', $tender)
            ->with('status', 'OEM follow-up raised.');
    }
}
