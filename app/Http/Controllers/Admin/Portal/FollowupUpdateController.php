<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\OemFollowupStatus;
use App\Http\Controllers\Admin\Portal\Concerns\StoresPrivateFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreFollowupUpdateRequest;
use App\Models\Portal\OemFollowup;
use Illuminate\Http\RedirectResponse;

/**
 * Contact attempts on an OEM follow-up (§11).
 *
 * Append-only, like task updates: there is no edit or delete route, so "we
 * chased them four times and they still have not sent the MAF" stays provable
 * when a bid has to be abandoned.
 */
class FollowupUpdateController extends Controller
{
    use StoresPrivateFiles;

    public function store(StoreFollowupUpdateRequest $request, OemFollowup $oem_followup): RedirectResponse
    {
        $data = $request->validated();
        $statusBefore = $oem_followup->status;
        $statusAfter = isset($data['status']) ? OemFollowupStatus::from($data['status']) : $statusBefore;

        $update = $oem_followup->followupUpdates()->create([
            'employee_id' => $request->user()->portalEmployee()->id,
            'note' => $data['note'],
            'status_from' => $statusBefore->value,
            'status_to' => $statusAfter->value,
            'contacted_on' => $data['contacted_on'] ?? now(),
            'next_followup_at' => $data['next_followup_at'] ?? null,
        ]);

        $oem_followup->update([
            'status' => $statusAfter,
            // A chase with no next date set leaves the existing one alone
            // rather than silently clearing the reminder.
            'next_followup_at' => $data['next_followup_at'] ?? $oem_followup->next_followup_at,
        ]);

        $this->storeOptionalUpload($update, 'oem/'.$oem_followup->id);

        return redirect()
            ->route('admin.portal.oem-followups.show', $oem_followup)
            ->with('status', 'Follow-up logged.');
    }
}
