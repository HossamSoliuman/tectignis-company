<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
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
