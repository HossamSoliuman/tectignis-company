<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeadNoteController extends Controller
{
    /**
     * Add a private internal note, stamped with author and time.
     */
    public function store(Request $request, Lead $lead): RedirectResponse
    {
        Gate::authorize('update', $lead);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ], [], ['body' => 'note']);

        $lead->notes()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $lead->recordActivity(LeadActivity::NOTE_ADDED, $request->user());

        return back()->with('status', 'Note added.');
    }
}
