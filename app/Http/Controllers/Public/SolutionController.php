<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Solution;
use Illuminate\Contracts\View\View;

class SolutionController extends Controller
{
    public function index(): View
    {
        $solutions = Solution::active()->ordered()->get();

        return view('public.solutions.index', compact('solutions'));
    }

    public function show(string $slug): View
    {
        $solution = Solution::active()->where('slug', $slug)->firstOrFail();

        return view('public.solutions.show', compact('solution'));
    }
}
