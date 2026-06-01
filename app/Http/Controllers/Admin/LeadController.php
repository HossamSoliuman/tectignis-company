<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    public function index(): View
    {
        $leads = Lead::latest()->paginate(20);

        return view('admin.leads.index', compact('leads'));
    }

    public function show(Lead $lead): View
    {
        if (! $lead->is_read) {
            $lead->update(['is_read' => true]);
        }

        return view('admin.leads.show', compact('lead'));
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead deleted.');
    }
}
