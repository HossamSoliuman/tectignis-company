<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobApplicationActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JobApplicationNoteController extends Controller
{
    /**
     * Add a private recruiter note, stamped with author and time.
     */
    public function store(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        Gate::authorize('update', $jobApplication);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ], [], ['body' => 'note']);

        $jobApplication->notes()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $jobApplication->recordActivity(JobApplicationActivity::NOTE_ADDED, $request->user());

        return back()->with('status', 'Note added.');
    }
}
