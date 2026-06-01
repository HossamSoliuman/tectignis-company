<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCaseStudyRequest;
use App\Http\Requests\Admin\UpdateCaseStudyRequest;
use App\Models\CaseStudy;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CaseStudyController extends Controller
{
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

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('assets/images/case-studies'), $data['image']);
        }

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

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('assets/images/case-studies'), $data['image']);
        }

        $caseStudy->update($data);

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study updated.');
    }

    public function destroy(CaseStudy $caseStudy): RedirectResponse
    {
        $caseStudy->delete();

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study deleted.');
    }
}
