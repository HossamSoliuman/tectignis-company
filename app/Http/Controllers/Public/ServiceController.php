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

        // Group into the three design categories (matches the nav/mega-menu), keeping
        // any service with an unknown/legacy category visible under "Other".
        $grouped = collect(Service::CATEGORY_LABELS)
            ->map(fn (string $label, string $key) => $services->where('category', $key)->values())
            ->filter->isNotEmpty();

        $ungrouped = $services->whereNotIn('category', array_keys(Service::CATEGORY_LABELS))->values();

        return view('public.services.index', [
            'grouped' => $grouped,
            'categoryLabels' => Service::CATEGORY_LABELS,
            'ungrouped' => $ungrouped,
        ]);
    }

    public function show(string $slug): View
    {
        $service = Service::active()
            ->with([
                'techStacks' => fn ($query) => $query->active()->ordered(),
                'industries' => fn ($query) => $query->active()->ordered(),
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.services.show', compact('service'));
    }
}
