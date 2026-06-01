<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactRequest;
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    public function submit(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Lead::create([
            'name' => $data['con_name'],
            'email' => $data['con_email'],
            'phone' => $data['con_phone'] ?? null,
            'subject' => $data['con_subject'] ?? null,
            'message' => $data['con_message'],
            'source' => 'contact',
        ]);

        Log::info('New lead from contact form', ['email' => $data['con_email']]);

        return back()->with('status', 'Thank you! We will be in touch shortly.');
    }
}
