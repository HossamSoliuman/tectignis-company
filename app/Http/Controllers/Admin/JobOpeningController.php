<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJobOpeningRequest;
use App\Http\Requests\Admin\UpdateJobOpeningRequest;
use App\Models\JobOpening;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class JobOpeningController extends Controller
{
    public function index(): View
    {
        $jobOpenings = JobOpening::ordered()->get();

        return view('admin.job-openings.index', compact('jobOpenings'));
    }

    public function create(): View
    {
        return view('admin.job-openings.create');
    }

    public function store(StoreJobOpeningRequest $request): RedirectResponse
    {
        JobOpening::create($request->validated());

        return redirect()->route('admin.job-openings.index')->with('status', 'Job opening created.');
    }

    public function edit(JobOpening $jobOpening): View
    {
        return view('admin.job-openings.edit', compact('jobOpening'));
    }

    public function update(UpdateJobOpeningRequest $request, JobOpening $jobOpening): RedirectResponse
    {
        $jobOpening->update($request->validated());

        return redirect()->route('admin.job-openings.index')->with('status', 'Job opening updated.');
    }

    public function destroy(JobOpening $jobOpening): RedirectResponse
    {
        $jobOpening->delete();

        return redirect()->route('admin.job-openings.index')->with('status', 'Job opening deleted.');
    }
}
