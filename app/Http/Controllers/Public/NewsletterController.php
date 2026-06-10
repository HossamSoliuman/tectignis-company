<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        Lead::create([
            'name' => 'Newsletter Subscriber',
            'email' => $data['email'],
            'subject' => 'Newsletter Subscription',
            'message' => 'Subscribed to the newsletter via '.url()->previous(),
            'source' => 'newsletter',
        ]);

        return back()->with('newsletter_status', 'Thank you for subscribing!');
    }
}
