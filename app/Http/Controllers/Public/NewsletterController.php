<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\LeadNotificationMail;
use App\Models\Lead;
use App\Rules\Recaptcha;
use App\Services\RecaptchaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            RecaptchaService::RESPONSE_FIELD => [new Recaptcha],
        ]);

        $lead = Lead::create([
            'name' => 'Newsletter Subscriber',
            'email' => $data['email'],
            'subject' => 'Newsletter Subscription',
            'message' => 'Subscribed to the newsletter via '.url()->previous(),
            'source' => 'newsletter',
        ]);

        LeadNotificationMail::dispatchFor($lead);

        return back()->with('newsletter_status', 'Thank you for subscribing!');
    }
}
