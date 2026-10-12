<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CandidateExperience;
use App\Enums\JobApplicationStatus;
use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobApplicationFilterRequest;
use App\Http\Requests\Admin\UpdateJobApplicationRequest;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobApplicationActivity;
use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The Applications module (spec §26.8): a filterable recruitment pipeline of
 * candidates, kept completely separate from Leads (spec §27).
 */
class JobApplicationController extends Controller
{
    use PaginatesTables;

    /**
     * Columns shown in the list — no SELECT * (spec §28.4).
     *
     * @var list<string>
     */
    private const LIST_COLUMNS = [
        'id', 'name', 'email', 'phone', 'job_opening_id', 'job_title', 'designation_name', 'location',
        'experience', 'status', 'assigned_to', 'is_read', 'archived_at', 'created_at',
    ];

    public function index(JobApplicationFilterRequest $request): View
    {
        Gate::authorize('viewAny', JobApplication::class);

        $filters = $request->filters();

        $applications = $this->baseQuery($request)
            ->select(self::LIST_COLUMNS)
            ->with('assignee:id,name')
            ->filter($filters)
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        // Pipeline counts honour every filter except the status itself.
        $statusCounts = $this->baseQuery($request)
            ->filter(collect($filters)->except('status')->all())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.job-applications.index', [
            'applications' => $applications,
            'statusCounts' => $statusCounts,
            'filters' => $filters,
            'archived' => $request->wantsArchived(),
            'jobOptions' => JobOpening::query()->orderBy('title')->get(['id', 'title']),
            'designationOptions' => Designation::query()->orderBy('name')->get(['id', 'name']),
            'assignees' => User::applicationAssignees()->get(['id', 'name']),
            'experiences' => CandidateExperience::options(),
            'statuses' => JobApplicationStatus::cases(),
        ]);
    }

    public function show(JobApplication $jobApplication): View
    {
        Gate::authorize('view', $jobApplication);

        if (! $jobApplication->is_read) {
            $jobApplication->update(['is_read' => true]);
        }

        $jobApplication->load(['jobOpening:id,title,slug,status', 'designation:id,name,is_active', 'assignee:id,name', 'notes.author:id,name', 'activities.user:id,name']);

        return view('admin.job-applications.show', [
            'application' => $jobApplication,
            'assignees' => User::applicationAssignees()->get(['id', 'name']),
        ]);
    }

    /**
     * Change status and/or assignment, auditing each change.
     */
    public function update(UpdateJobApplicationRequest $request, JobApplication $jobApplication): RedirectResponse
    {
        $user = $request->user();
        $status = JobApplicationStatus::from($request->validated('status'));
        $assigneeId = $request->validated('assigned_to');
        $assigneeId = $assigneeId !== null ? (int) $assigneeId : null;

        if ($status !== $jobApplication->status) {
            $jobApplication->recordActivity(JobApplicationActivity::STATUS_CHANGED, $user, $jobApplication->status->value, $status->value);
        }

        if ($assigneeId !== $jobApplication->assigned_to) {
            $previous = $jobApplication->assignee?->name;
            $next = $assigneeId ? User::find($assigneeId)?->name : null;
            $jobApplication->recordActivity(JobApplicationActivity::ASSIGNED, $user, $previous, $next);
        }

        $jobApplication->update(['status' => $status, 'assigned_to' => $assigneeId]);

        return back()->with('status', 'Application updated.');
    }

    /**
     * Move a finished application out of the working list (spec §26.8).
     */
    public function archive(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        Gate::authorize('update', $jobApplication);

        if (! $jobApplication->isArchived()) {
            $jobApplication->update(['archived_at' => now()]);
            $jobApplication->recordActivity(JobApplicationActivity::ARCHIVED, $request->user());
        }

        return redirect()->route('admin.job-applications.index')->with('status', 'Application archived.');
    }

    public function unarchive(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        Gate::authorize('update', $jobApplication);

        if ($jobApplication->isArchived()) {
            $jobApplication->update(['archived_at' => null]);
            $jobApplication->recordActivity(JobApplicationActivity::UNARCHIVED, $request->user());
        }

        return redirect()->route('admin.job-applications.show', $jobApplication)->with('status', 'Application restored from the archive.');
    }

    /**
     * Permanently erase an archived application together with its CV, for
     * retention clean-up or a candidate's deletion request (spec §26.13).
     */
    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        Gate::authorize('delete', $jobApplication);

        if ($jobApplication->hasResume()) {
            Storage::disk(JobApplication::RESUME_DISK)->delete($jobApplication->resume_path);
        }

        $jobApplication->delete();

        return redirect()->route('admin.job-applications.index', ['archived' => 1])->with('status', 'Application and CV permanently deleted.');
    }

    /**
     * Stream the CV from the private disk as an attachment (spec §26.13). Each
     * download is recorded in the application's audit trail.
     */
    public function resume(Request $request, JobApplication $jobApplication): StreamedResponse
    {
        Gate::authorize('view', $jobApplication);

        $disk = Storage::disk(JobApplication::RESUME_DISK);

        abort_unless($jobApplication->hasResume() && $disk->exists($jobApplication->resume_path), 404);

        $jobApplication->recordActivity(JobApplicationActivity::RESUME_DOWNLOADED, $request->user());

        return $disk->download($jobApplication->resume_path, $jobApplication->resumeDownloadName(), [
            'Content-Type' => $jobApplication->resume_mime ?: 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    /**
     * The working list, or only archived applications.
     *
     * @return Builder<JobApplication>
     */
    private function baseQuery(JobApplicationFilterRequest $request): Builder
    {
        return $request->wantsArchived() ? JobApplication::query()->archived() : JobApplication::query()->unarchived();
    }
}
