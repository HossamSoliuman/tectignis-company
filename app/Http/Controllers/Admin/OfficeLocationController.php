<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfficeLocationRequest;
use App\Http\Requests\Admin\UpdateOfficeLocationRequest;
use App\Models\OfficeLocation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OfficeLocationController extends Controller
{
    public function index(): View
    {
        $locations = OfficeLocation::orderBy('region')->ordered()->get();

        return view('admin.office-locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('admin.office-locations.create');
    }

    public function store(StoreOfficeLocationRequest $request): RedirectResponse
    {
        OfficeLocation::create($request->validated());

        return redirect()->route('admin.office-locations.index')->with('status', 'Location created.');
    }

    public function edit(OfficeLocation $officeLocation): View
    {
        return view('admin.office-locations.edit', ['location' => $officeLocation]);
    }

    public function update(UpdateOfficeLocationRequest $request, OfficeLocation $officeLocation): RedirectResponse
    {
        $officeLocation->update($request->validated());

        return redirect()->route('admin.office-locations.index')->with('status', 'Location updated.');
    }

    public function destroy(OfficeLocation $officeLocation): RedirectResponse
    {
        $officeLocation->delete();

        return redirect()->route('admin.office-locations.index')->with('status', 'Location deleted.');
    }
}
