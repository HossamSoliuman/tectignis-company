<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\TenderStage;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Controller;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderSubmissionCheck;
use App\Services\Portal\TenderCompletionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * The final pre-submission checklist (§14).
 *
 * This is the tab that stops a bid going up with the price sheet in the
 * technical envelope. Marking a tender submitted is deliberately gated: every
 * line ticked, and no mandatory document still outstanding.
 */
class TenderSubmissionController extends Controller
{
    use ResolvesTenderWorkspace;

    public function index(Tender $tender, TenderCompletionService $completion): View
    {
        $checks = $tender->submissionChecks()->with('checkedBy:id,name')->get();

        return view('admin.portal.tenders.tabs.submission', $this->viewWorkspace($tender, 'submission', [
            'checks' => $checks,
            'remaining' => $checks->where('is_checked', false)->count(),
            'missingMandatory' => $completion->missingMandatoryDocuments($tender),
            'canSubmit' => $checks->isNotEmpty()
                && $checks->where('is_checked', false)->isEmpty()
                && $completion->isReadyToSubmit($tender),
        ]));
    }

    /**
     * Tick or untick a line, recording who did it and when.
     */
    public function update(Request $request, Tender $tender, TenderSubmissionCheck $submission_check): RedirectResponse
    {
        $this->authorize('update', $tender);

        $checked = $request->boolean('is_checked');

        $submission_check->update([
            'is_checked' => $checked,
            'checked_by_id' => $checked ? $request->user()->portalEmployee()->id : null,
            'checked_at' => $checked ? now() : null,
        ]);

        return $this->back($tender, $checked ? 'Check confirmed.' : 'Check cleared.');
    }

    /**
     * Move the tender to Submitted — only once the checklist genuinely allows
     * it. A blocked attempt says why rather than failing silently.
     */
    public function markSubmitted(Tender $tender, TenderCompletionService $completion): RedirectResponse
    {
        $this->authorize('update', $tender);

        $outstanding = $tender->submissionChecks()->where('is_checked', false)->count();
        $missing = $completion->missingMandatoryDocuments($tender);

        if ($outstanding > 0 || $missing > 0) {
            return $this->back($tender, null)->withErrors([
                'submission' => $outstanding > 0
                    ? "{$outstanding} checklist line(s) are still unticked."
                    : "{$missing} mandatory document(s) are still outstanding.",
            ]);
        }

        $tender->update(['stage' => TenderStage::Submitted]);

        $completion->refresh($tender);

        return $this->back($tender, "Tender {$tender->code} marked as submitted.");
    }

    private function back(Tender $tender, ?string $message): RedirectResponse
    {
        $redirect = redirect()->route('admin.portal.tenders.submission.index', $tender);

        return $message === null ? $redirect : $redirect->with('status', $message);
    }
}
