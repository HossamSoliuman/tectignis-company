<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Contracts\View\View;

class IndustryController extends Controller
{
    public function index(): View
    {
        $industries = Industry::active()->ordered()->get();

        return view('public.industries.index', compact('industries'));
    }

    public function show(string $slug): View
    {
        $industry = Industry::active()->where('slug', $slug)->firstOrFail();

        return view('public.industries.show', compact('industry'));
    }
}
