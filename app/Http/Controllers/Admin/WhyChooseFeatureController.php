<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWhyChooseFeatureRequest;
use App\Http\Requests\Admin\UpdateWhyChooseFeatureRequest;
use App\Models\WhyChooseFeature;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WhyChooseFeatureController extends Controller
{
    public function index(): View
    {
        $features = WhyChooseFeature::ordered()->get();

        return view('admin.why-choose-features.index', compact('features'));
    }

    public function create(): View
    {
        return view('admin.why-choose-features.create');
    }

    public function store(StoreWhyChooseFeatureRequest $request): RedirectResponse
    {
        WhyChooseFeature::create($request->validated());

        return redirect()->route('admin.why-choose-features.index')->with('status', 'Feature created.');
    }

    public function edit(WhyChooseFeature $whyChooseFeature): View
    {
        return view('admin.why-choose-features.edit', ['feature' => $whyChooseFeature]);
    }

    public function update(UpdateWhyChooseFeatureRequest $request, WhyChooseFeature $whyChooseFeature): RedirectResponse
    {
        $whyChooseFeature->update($request->validated());

        return redirect()->route('admin.why-choose-features.index')->with('status', 'Feature updated.');
    }

    public function destroy(WhyChooseFeature $whyChooseFeature): RedirectResponse
    {
        $whyChooseFeature->delete();

        return redirect()->route('admin.why-choose-features.index')->with('status', 'Feature deleted.');
    }
}
