<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\ClarificationStatus;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Admin\Portal\Concerns\StoresPrivateFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreTenderClarificationRequest;
use App\Http\Requests\Admin\Portal\UpdateTenderClarificationRequest;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderClarification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Pre-bid queries put to the tendering authority and the answers that came
 * back (§13).
 *
 * A question with no answer as the deadline approaches is a risk somebody has
 * to decide about, so it is tracked here rather than left in an inbox.
 */
class TenderClarificationController extends Controller
{
    use ResolvesTenderWorkspace, StoresPrivateFiles;

    public function index(Tender $tender): View
    {
        return view('admin.portal.tenders.tabs.clarifications', $this->viewWorkspace($tender, 'clarifications', [
            'clarifications' => $tender->clarifications()
                ->with(['raisedBy:id,name', 'attachment'])
                ->orderByDesc('id')
                ->get(),
            'clarificationStatuses' => ClarificationStatus::options(),
        ]));
    }

    public function store(StoreTenderClarificationRequest $request, Tender $tender): RedirectResponse
    {
        $tender->clarifications()->create([
            ...$request->validated(),
            'raised_by_id' => $request->user()->portalEmployee()->id,
            'raised_on' => $request->validated('raised_on') ?? now()->toDateString(),
            'status' => ClarificationStatus::Pending,
        ]);

        return $this->back($tender, 'Clarification recorded.');
    }

    /**
     * Record the authority's answer. The reply document, if there is one, is
     * kept alongside so the answer can be quoted later.
     */
    public function update(UpdateTenderClarificationRequest $request, Tender $tender, TenderClarification $clarification): RedirectResponse
    {
        $data = $request->validated();
        unset($data['attachment']);

        $attachment = $this->storeOptionalUpload($clarification, 'tenders/'.$tender->id.'/clarifications');

        if ($attachment !== null) {
            $data['attachment_id'] = $attachment->id;
        }

        if (filled($data['response'] ?? null) && blank($data['response_on'] ?? null)) {
            $data['response_on'] = now()->toDateString();
        }

        $clarification->update($data);

        return $this->back($tender, 'Clarification updated.');
    }

    public function destroy(Tender $tender, TenderClarification $clarification): RedirectResponse
    {
        $this->authorize('update', $tender);

        $clarification->delete();

        return $this->back($tender, 'Clarification removed.');
    }

    private function back(Tender $tender, string $message): RedirectResponse
    {
        return redirect()
            ->route('admin.portal.tenders.clarifications.index', $tender)
            ->with('status', $message);
    }
}
