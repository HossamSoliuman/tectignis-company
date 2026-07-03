<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use Illuminate\Contracts\View\View;

class CaseStudyController extends Controller
{
    public function index(): View
    {
        $caseStudies = CaseStudy::with('category')->active()->ordered()->get();

        return view('public.case-studies.index', compact('caseStudies'));
    }

    public function show(CaseStudy $caseStudy): View
    {
        abort_unless($caseStudy->is_active, 404);

        $caseStudy->loadMissing('category');

        $relatedCaseStudies = CaseStudy::with('category')
            ->active()
            ->whereKeyNot($caseStudy->getKey())
            ->ordered()
            ->limit(3)
            ->get();

        return view('public.case-studies.show', compact('caseStudy', 'relatedCaseStudies'));
    }
}
