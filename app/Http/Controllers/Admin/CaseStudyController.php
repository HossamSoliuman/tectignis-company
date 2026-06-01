<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCaseStudyRequest;
use App\Http\Requests\Admin\UpdateCaseStudyRequest;
use App\Models\CaseStudy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CaseStudyController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $caseStudies = CaseStudy::ordered()->get();

        return view('admin.case-studies.index', compact('caseStudies'));
    }

    public function create(): View
    {
        return view('admin.case-studies.create');
    }

    public function store(StoreCaseStudyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'image');

        CaseStudy::create($data);

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study created.');
    }

    public function edit(CaseStudy $caseStudy): View
    {
        return view('admin.case-studies.edit', compact('caseStudy'));
    }

    public function update(UpdateCaseStudyRequest $request, CaseStudy $caseStudy): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'image', $caseStudy->image);

        $caseStudy->update($data);

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study updated.');
    }

    public function destroy(CaseStudy $caseStudy): RedirectResponse
    {
        $this->deleteUpload($caseStudy->image);
        $caseStudy->delete();

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study deleted.');
    }
}
