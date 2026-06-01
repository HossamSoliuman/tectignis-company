<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class CapabilityController extends Controller
{
    public function index(): View
    {
        $services = Service::active()->ordered()->get();

        return view('public.capabilities.index', compact('services'));
    }

    public function show(string $slug): View
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();

        return view('public.capabilities.show', compact('service'));
    }
}
