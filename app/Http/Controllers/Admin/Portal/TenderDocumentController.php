<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\TenderDocumentCategory;
use App\Enums\Portal\TenderDocumentStatus;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Admin\Portal\Concerns\StoresPrivateFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreTenderDocumentRequest;
use App\Http\Requests\Admin\Portal\UpdateTenderDocumentRequest;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderDocument;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * The document checklist tab (§10, §17).
 *
 * A row here is the *requirement*; the file that satisfies it is an Attachment.
 * Re-uploading bumps the version and keeps every earlier file, so a rejected
 * revision can still be produced months later.
 */
class TenderDocumentController extends Controller
{
    use ResolvesTenderWorkspace, StoresPrivateFiles;

    public function index(Tender $tender): View
    {
        $documents = $tender->documents()
            ->with(['responsible:id,name', 'verifier:id,name', 'attachment'])
            ->get();

        return view('admin.portal.tenders.tabs.documents', $this->viewWorkspace($tender, 'documents', [
            'documents' => $documents,
            'grouped' => $documents->groupBy(fn (TenderDocument $document): string => $document->category->value),
            'categories' => TenderDocumentCategory::options(),
            'documentStatuses' => TenderDocumentStatus::options(),
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
        ]));
    }

    public function store(StoreTenderDocumentRequest $request, Tender $tender): RedirectResponse
    {
        $tender->documents()->create([
            ...$request->validated(),
            'status' => TenderDocumentStatus::Pending,
            'sort_order' => (int) $tender->documents()->max('sort_order') + 1,
        ]);

        return $this->back($tender, 'Document requirement added.');
    }

    /**
     * Update the line and, if a file came with it, attach the new version.
     * A fresh upload always invalidates an earlier sign-off — verification
     * applies to the file that was checked, not to the row.
     */
    public function update(UpdateTenderDocumentRequest $request, Tender $tender, TenderDocument $document): RedirectResponse
    {
        $data = $request->validated();
        unset($data['attachment']);

        $attachment = $this->storeOptionalUpload($document, 'tenders/'.$tender->id.'/documents');

        if ($attachment !== null) {
            $data['attachment_id'] = $attachment->id;
            $data['version'] = $attachment->version;
            $data['verified_by_id'] = null;
            $data['verified_at'] = null;
        }

        $document->update($data);

        return $this->back($tender, $attachment !== null
            ? "New version of {$document->name} uploaded — it needs verifying again."
            : "{$document->name} updated.");
    }

    /**
     * Management sign-off (§10). Separate from the upload on purpose: the
     * person who produced a document is not the person who approves it.
     */
    public function verify(Tender $tender, TenderDocument $document): RedirectResponse
    {
        $this->authorize('verifyDocuments', $tender);

        $document->update([
            'verified_by_id' => request()->user()->portalEmployee()->id,
            'verified_at' => now(),
            'status' => TenderDocumentStatus::Ready,
        ]);

        return $this->back($tender, "{$document->name} verified.");
    }

    public function destroy(Tender $tender, TenderDocument $document): RedirectResponse
    {
        $this->authorize('update', $tender);

        $document->delete();

        return $this->back($tender, 'Document requirement removed.');
    }

    private function back(Tender $tender, string $message): RedirectResponse
    {
        return redirect()
            ->route('admin.portal.tenders.documents.index', $tender)
            ->with('status', $message);
    }
}
