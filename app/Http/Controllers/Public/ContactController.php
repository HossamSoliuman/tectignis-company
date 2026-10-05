<?php

namespace App\Http\Controllers\Public;

use App\Enums\LeadSource;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CaptureLeadAttribution;
use App\Http\Requests\Public\ContactRequest;
use App\Mail\LeadNotificationMail;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    /**
     * Validate → store → notify → thank-you page (spec §26.1). The lead is
     * saved before any mail is attempted, so a mail failure never loses it.
     */
    public function submit(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $source = $request->isConsultation() ? LeadSource::Consultation : LeadSource::Contact;

        $lead = Lead::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'],
            'country' => $data['country'],
            'phone' => $data['phone'] ?? null,
            'service' => $data['service'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'budget' => $data['budget'] ?? null,
            'timeline' => $data['timeline'] ?? null,
            'source' => $source->value,
            'page_url' => $data['page_url'] ?? url()->previous(),
            'consented_at' => now(),
            ...CaptureLeadAttribution::fromSession($request),
        ]);

        $lead->recordActivity(LeadActivity::CREATED);

        LeadNotificationMail::dispatchFor($lead);

        Log::info('New lead from public form', ['lead_id' => $lead->id, 'source' => $source->value]);

        return redirect()
            ->route('contact.thank-you')
            ->with('enquiry_submitted', [
                'name' => $lead->name,
                'service' => $lead->service,
            ]);
    }

    /**
     * Confirmation page shown only straight after a successful submission, so
     * the page view doubles as a reliable conversion signal.
     */
    public function thankYou(): View|RedirectResponse
    {
        $enquiry = session('enquiry_submitted');

        if (! is_array($enquiry)) {
            return redirect()->route('contact');
        }

        return view('public.thank-you', ['enquiry' => $enquiry]);
    }
}
