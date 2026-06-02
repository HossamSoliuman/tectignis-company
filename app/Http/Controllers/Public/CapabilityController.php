<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Capability;
use Illuminate\Contracts\View\View;

class CapabilityController extends Controller
{
    public function index(): View
    {
        $capabilities = Capability::active()->ordered()->get();

        return view('public.capabilities.index', compact('capabilities'));
    }

    public function show(string $slug): View
    {
        $capability = Capability::active()->where('slug', $slug)->firstOrFail();

        return view('public.capabilities.show', compact('capability'));
    }
}
