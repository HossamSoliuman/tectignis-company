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
}
