<?php

namespace App\Http\Controllers\Admin;

use App\Enums\JobOpeningStatus;
use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJobOpeningRequest;
use App\Http\Requests\Admin\UpdateJobOpeningRequest;
use App\Models\JobOpening;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Job postings (spec §26.8–26.9): create, publish, unpublish and close.
 */
class JobOpeningController extends Controller
{
    use PaginatesTables;

    public function index(Request $request): View
    {
        $status = JobOpeningStatus::tryFrom((string) $request->query('status'));

        $query = JobOpening::query()
            ->select(['id', 'title', 'slug', 'department', 'location', 'employment_type', 'status', 'application_deadline', 'sort_order'])
            ->withCount('applications')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->ordered();

        return view('admin.job-openings.index', [
            'jobOpenings' => $this->paginateTable($request, $query, ['title', 'department', 'location']),
            'activeStatus' => $status,
            'statusCounts' => JobOpening::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
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
        $data = $request->validated();

        // A blank slug keeps the current URL rather than regenerating it.
        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        $jobOpening->update($data);

        return redirect()->route('admin.job-openings.index')->with('status', 'Job opening updated.');
    }

    /**
     * Openings with applications are kept for the candidate history (spec
     * §28.11); close them instead.
     */
    public function destroy(JobOpening $jobOpening): RedirectResponse
    {
        if ($jobOpening->applications()->exists()) {
            return back()->withErrors(['job_opening' => 'This opening has applications, so it cannot be deleted. Set its status to Closed instead.']);
        }

        $jobOpening->delete();

        return redirect()->route('admin.job-openings.index')->with('status', 'Job opening deleted.');
    }
}
