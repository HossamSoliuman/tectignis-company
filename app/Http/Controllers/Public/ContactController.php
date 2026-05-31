<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    /**
     * Validate the enquiry. Lead persistence + mail are wired in a later phase
     * once the leads table exists; for now we validate and confirm to the user.
     */
    public function submit(ContactRequest $request): RedirectResponse
    {
        $request->validated();

        return back()->with('status', "Thank you! Your message has been received. We'll get back to you shortly.");
    }
}
