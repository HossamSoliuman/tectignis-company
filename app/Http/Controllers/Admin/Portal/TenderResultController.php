<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\TenderOutcome;
use App\Enums\Portal\TenderStage;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Admin\Portal\Concerns\StoresPrivateFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreTenderResultRequest;
use App\Models\Portal\Tender;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * How the bid ended (§7).
 *
 * One row per tender — recording a result again corrects the existing one
 * rather than starting a second history, and the change is captured by the
 * activity log either way. This is the input to Phase 4's win-rate reporting.
 */
class TenderResultController extends Controller
{
    use ResolvesTenderWorkspace, StoresPrivateFiles;

    public function index(Tender $tender): View
    {
        return view('admin.portal.tenders.tabs.result', $this->viewWorkspace($tender, 'result', [
            'result' => $tender->result()->with(['recordedBy:id,name', 'attachments'])->first(),
            'outcomes' => TenderOutcome::options(),
            'canRecord' => request()->user()->can('recordResult', $tender),
        ]));
    }

    public function store(StoreTenderResultRequest $request, Tender $tender): RedirectResponse
    {
        $data = $request->validated();
        unset($data['attachment']);

        $result = $tender->result()->updateOrCreate(
            ['tender_id' => $tender->id],
            [...$data, 'recorded_by_id' => $request->user()->portalEmployee()->id],
        );

        $this->storeOptionalUpload($result, 'tenders/'.$tender->id.'/result');

        // A decided outcome closes the bid; "pending" leaves it awaiting one.
        $tender->update([
            'stage' => $result->outcome === TenderOutcome::Pending
                ? TenderStage::ResultAwaited
                : TenderStage::Closed,
        ]);

        return redirect()
            ->route('admin.portal.tenders.result.index', $tender)
            ->with('status', "Result recorded: {$result->outcome->label()}.");
    }
}
