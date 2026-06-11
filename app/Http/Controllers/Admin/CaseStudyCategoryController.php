<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCaseStudyCategoryRequest;
use App\Http\Requests\Admin\UpdateCaseStudyCategoryRequest;
use App\Models\CaseStudyCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CaseStudyCategoryController extends Controller
{
    public function index(): View
    {
        $categories = CaseStudyCategory::withCount('caseStudies')->ordered()->get();

        return view('admin.case-study-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.case-study-categories.create');
    }

    public function store(StoreCaseStudyCategoryRequest $request): RedirectResponse
    {
        CaseStudyCategory::create($request->validated());

        return redirect()->route('admin.case-study-categories.index')->with('status', 'Case study category created.');
    }

    public function edit(CaseStudyCategory $caseStudyCategory): View
    {
        return view('admin.case-study-categories.edit', compact('caseStudyCategory'));
    }

    public function update(UpdateCaseStudyCategoryRequest $request, CaseStudyCategory $caseStudyCategory): RedirectResponse
    {
        $caseStudyCategory->update($request->validated());

        return redirect()->route('admin.case-study-categories.index')->with('status', 'Case study category updated.');
    }

    public function destroy(CaseStudyCategory $caseStudyCategory): RedirectResponse
    {
        $caseStudyCategory->delete();

        return redirect()->route('admin.case-study-categories.index')->with('status', 'Case study category deleted.');
    }
}
