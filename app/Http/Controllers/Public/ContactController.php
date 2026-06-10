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

        $isConsultation = $request->isConsultation();

        $message = $data['con_message'];
        if (! empty($data['con_company'])) {
            $message = 'Company: '.$data['con_company']."\n\n".$message;
        }

        Lead::create([
            'name' => $data['con_name'],
            'email' => $data['con_email'] ?? null,
            'phone' => $data['con_phone'] ?? null,
            'subject' => $data['con_subject'] ?? null,
            'message' => $message,
            'source' => $isConsultation ? 'consultation' : 'contact',
        ]);

        Log::info('New lead from public form', [
            'source' => $isConsultation ? 'consultation' : 'contact',
            'email' => $data['con_email'] ?? null,
        ]);

        $thankYou = 'Thank you! We will be in touch shortly.';

        return $isConsultation
            ? back()->with('consultation_status', $thankYou)
            : back()->with('status', $thankYou);
    }
}
