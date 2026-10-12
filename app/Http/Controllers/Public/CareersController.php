<?php

namespace App\Http\Controllers\Public;

use App\Enums\JobOpeningStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreJobApplicationRequest;
use App\Mail\JobApplicationNotificationMail;
use App\Models\Designation;
use App\Models\JobApplication;
use App\Models\JobApplicationActivity;
use App\Models\JobOpening;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * The public careers pages and application form (spec §26.9–26.11). An
 * application is stored in the Careers module, never as a lead (spec §27).
 */
class CareersController extends Controller
{
    public function index(): View
    {
        return view('public.careers.index', [
            'jobOpenings' => JobOpening::open()->ordered()->get(),
            'designations' => $this->activeDesignations(),
        ]);
    }

    /**
     * A posting's own page. Closed postings stay readable without the form;
     * drafts are not public.
     */
    public function show(JobOpening $jobOpening): View
    {
        abort_if($jobOpening->status === JobOpeningStatus::Draft, 404);

        return view('public.careers.show', [
            'jobOpening' => $jobOpening,
            'designations' => $this->activeDesignations(),
            'otherOpenings' => JobOpening::open()->ordered()->whereKeyNot($jobOpening->getKey())->limit(4)->get(),
        ]);
    }

    public function submit(StoreJobApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $resume = $request->file('resume');

        // Unguessable name and a content-derived extension; the client's file
        // name is only kept as a label.
        $path = $resume->storeAs('resumes/'.now()->format('Y/m'), Str::random(40).'.'.$resume->extension(), JobApplication::RESUME_DISK);

        if ($path === false) {
            return back()
                ->withInput($request->except('resume'))
                ->withErrors(['resume' => 'We could not save your CV. Please try again.'], 'application');
        }

        try {
            $application = JobApplication::create([
                ...collect($data)->only([
                    'name', 'email', 'phone', 'location', 'experience', 'current_role', 'notice_period',
                    'linkedin_url', 'portfolio_url', 'cover_letter',
                ])->all(),
                ...$this->appliedFor($data),
                'resume_path' => $path,
                'resume_name' => Str::limit($resume->getClientOriginalName(), 250, ''),
                'resume_mime' => $resume->getMimeType(),
                'resume_size' => $resume->getSize(),
                'consented_at' => now(),
            ]);
        } catch (Throwable $e) {
            Storage::disk(JobApplication::RESUME_DISK)->delete($path);

            throw $e;
        }

        $application->recordActivity(JobApplicationActivity::CREATED);

        JobApplicationNotificationMail::dispatchFor($application);

        return back()->with('career_status', 'Thank you! Your application '.$application->reference().' has been received. Our HR team will be in touch if your profile is a match.');
    }

    /**
     * Snapshot the vacancy and designation names so the record survives
     * later renames and deactivations (spec §28.9).
     *
     * @param  array<string, mixed>  $data
     * @return array{job_opening_id: ?int, job_title: ?string, designation_id: int, designation_name: string}
     */
    private function appliedFor(array $data): array
    {
        $opening = filled($data['job_opening_id'] ?? null) ? JobOpening::find($data['job_opening_id']) : null;
        $designation = Designation::findOrFail($data['designation_id']);

        return [
            'job_opening_id' => $opening?->id,
            'job_title' => $opening?->title,
            'designation_id' => $designation->id,
            'designation_name' => $designation->name,
        ];
    }

    /**
     * @return Collection<int, Designation>
     */
    private function activeDesignations(): Collection
    {
        return Designation::active()->ordered()->get(['id', 'name']);
    }
}
