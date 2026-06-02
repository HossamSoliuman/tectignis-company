<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::active()->ordered()->get();

        return view('public.services.index', compact('services'));
    }

    public function show(string $slug): View
    {
        $service = Service::active()->where('slug', $slug)->firstOrFail();

        return view('public.services.show', compact('service'));
    }
}
