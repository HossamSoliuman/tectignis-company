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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            'company' => $data['company'] ?? null,
            'country' => $request->country(),
            'phone' => $request->phoneNumber(),
            'service' => $data['service'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'] ?? '',
            'source' => $source->value,
            'page_url' => $data['page_url'] ?? url()->previous(),
            'landing_page' => $request->landingPageSlug(),
            'language' => app()->getLocale(),
            'consented_at' => now(),
            ...CaptureLeadAttribution::fromSession($request),
        ]);

        $lead->recordActivity(LeadActivity::CREATED);

        LeadNotificationMail::dispatchFor($lead);

        Log::info('New lead from public form', ['lead_id' => $lead->id, 'source' => $source->value, 'landing_page' => $lead->landing_page]);

        return redirect()
            ->to(localized_route('contact.thank-you'))
            ->with('enquiry_submitted', [
                'name' => $lead->name,
                'service' => $lead->service,
                'source' => $lead->source,
                'landing_page' => $lead->landing_page,
                'language' => $lead->language,
                'conversion_id' => (string) Str::uuid(),
            ]);
    }

    /**
     * Confirmation page shown only straight after a successful submission, so
     * the page view doubles as a reliable conversion signal. It is never
     * cached, so going back to it cannot replay the conversion.
     */
    public function thankYou(): Response|RedirectResponse
    {
        $enquiry = session('enquiry_submitted');

        if (! is_array($enquiry)) {
            return redirect()->to(localized_route('contact'));
        }

        return response()
            ->view('public.thank-you', ['enquiry' => $enquiry, 'conversion' => $this->conversion($enquiry)])
            ->header('Cache-Control', 'no-store, private');
    }

    /**
     * The `generate_lead` event parameters. Only what describes the enquiry,
     * never who sent it; `transaction_id` is random, so Google Ads can drop a
     * repeat of the same conversion without it pointing back to the lead.
     *
     * @param  array<string, mixed>  $enquiry
     * @return array{transaction_id: string, service: ?string, lead_source: ?string, landing_page: ?string, page_language: string}
     */
    private function conversion(array $enquiry): array
    {
        return [
            'transaction_id' => $enquiry['conversion_id'] ?? (string) Str::uuid(),
            'service' => $enquiry['service'] ?? null,
            'lead_source' => $enquiry['source'] ?? null,
            'landing_page' => $enquiry['landing_page'] ?? null,
            'page_language' => $enquiry['language'] ?? app()->getLocale(),
        ];
    }
}
