<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\TenderDocumentCategory;
use App\Enums\Portal\TenderDocumentStatus;
use App\Http\Controllers\Admin\Portal\Concerns\ResolvesTenderWorkspace;
use App\Http\Controllers\Controller;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use Illuminate\Contracts\View\View;

/**
 * The technical and commercial preparation tabs (§12).
 *
 * Both are focused views of the same document checklist: the technical team
 * should not have to read past the price sheet to find the compliance matrix,
 * and the commercial team should not have to scroll through datasheets to reach
 * the BOQ. Editing still happens through TenderDocumentController, so there is
 * one write path for a document line.
 */
class TenderPreparationController extends Controller
{
    use ResolvesTenderWorkspace;

    public function technical(Tender $tender): View
    {
        return $this->preparationTab($tender, 'technical', TenderDocumentCategory::technical());
    }

    public function commercial(Tender $tender): View
    {
        return $this->preparationTab($tender, 'commercial', TenderDocumentCategory::commercial());
    }

    /**
     * @param  array<int, TenderDocumentCategory>  $categories
     */
    private function preparationTab(Tender $tender, string $tab, array $categories): View
    {
        $values = array_column($categories, 'value');

        $documents = $tender->documents()
            ->whereIn('category', $values)
            ->with(['responsible:id,name', 'verifier:id,name', 'attachment'])
            ->get();

        return view('admin.portal.tenders.tabs.'.$tab, $this->viewWorkspace($tender, $tab, [
            'documents' => $documents,
            'categories' => $categories,
            'documentStatuses' => TenderDocumentStatus::options(),
            'employees' => Employee::active()->orderBy('name')->get(['id', 'name']),
            'tasks' => $tender->tasks()
                ->open()
                ->with('assignee:id,name')
                ->orderByRaw('due_date is null, due_date')
                ->get(),
        ]));
    }
}
