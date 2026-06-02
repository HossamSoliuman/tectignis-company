<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCapabilityRequest;
use App\Http\Requests\Admin\UpdateCapabilityRequest;
use App\Models\Capability;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CapabilityController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $capabilities = Capability::ordered()->get();

        return view('admin.capabilities.index', compact('capabilities'));
    }

    public function create(): View
    {
        return view('admin.capabilities.create');
    }

    public function store(StoreCapabilityRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'icon');
        $this->syncUpload($request, $data, 'banner_image');

        Capability::create($data);

        return redirect()->route('admin.capabilities.index')->with('status', 'Capability created.');
    }

    public function edit(Capability $capability): View
    {
        return view('admin.capabilities.edit', compact('capability'));
    }

    public function update(UpdateCapabilityRequest $request, Capability $capability): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'icon', $capability->icon);
        $this->syncUpload($request, $data, 'banner_image', $capability->banner_image);

        $capability->update($data);

        return redirect()->route('admin.capabilities.index')->with('status', 'Capability updated.');
    }

    public function destroy(Capability $capability): RedirectResponse
    {
        $this->deleteUpload($capability->icon);
        $this->deleteUpload($capability->banner_image);
        $capability->delete();

        return redirect()->route('admin.capabilities.index')->with('status', 'Capability deleted.');
    }
}
