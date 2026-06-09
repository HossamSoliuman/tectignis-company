<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesIndustryContent;
use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreIndustryRequest;
use App\Http\Requests\Admin\UpdateIndustryRequest;
use App\Models\Industry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class IndustryController extends Controller
{
    use ManagesIndustryContent, UploadsFiles;

    public function index(): View
    {
        $industries = Industry::ordered()->get();

        return view('admin.industries.index', compact('industries'));
    }

    public function create(): View
    {
        return view('admin.industries.create');
    }

    public function store(StoreIndustryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'banner_image', null, 'industries');
        $this->prepareContent($request, $data);

        $industry = Industry::create($data);

        return redirect()->route('admin.industries.edit', $industry)->with('status', 'Industry created.');
    }

    public function edit(Industry $industry): View
    {
        return view('admin.industries.edit', compact('industry'));
    }

    public function update(UpdateIndustryRequest $request, Industry $industry): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'banner_image', $industry->banner_image, 'industries');
        $this->prepareContent($request, $data);

        $industry->update($data);

        return redirect()->route('admin.industries.edit', $industry)->with('status', 'Industry updated.');
    }

    public function destroy(Industry $industry): RedirectResponse
    {
        $this->deleteUpload($industry->banner_image);
        $industry->delete();

        return redirect()->route('admin.industries.index')->with('status', 'Industry deleted.');
    }
}
