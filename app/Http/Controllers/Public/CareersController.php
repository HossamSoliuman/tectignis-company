<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Mail\LeadNotificationMail;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CareersController extends Controller
{
    use UploadsFiles;

    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'con_name' => ['required', 'string', 'max:255'],
            'con_email' => ['required', 'email', 'max:255'],
            'con_phone' => ['required', 'string', 'max:20'],
            'con_position' => ['nullable', 'string', 'max:255'],
            'con_experience' => ['nullable', 'string', 'max:50'],
            'con_location' => ['nullable', 'string', 'max:255'],
            'con_notice_period' => ['nullable', 'string', 'max:50'],
            'con_resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'con_message' => ['nullable', 'string', 'max:5000'],
        ], [], [
            'con_name' => 'full name',
            'con_email' => 'email address',
            'con_phone' => 'phone number',
            'con_position' => 'position',
            'con_experience' => 'total experience',
            'con_location' => 'current location',
            'con_notice_period' => 'notice period',
            'con_resume' => 'resume',
            'con_message' => 'message',
        ]);

        $details = [];
        foreach ([
            'Total Experience' => $data['con_experience'] ?? null,
            'Current Location' => $data['con_location'] ?? null,
            'Notice Period' => $data['con_notice_period'] ?? null,
        ] as $label => $value) {
            if (filled($value)) {
                $details[] = $label.': '.$value;
            }
        }

        $message = implode("\n", $details);
        if (filled($data['con_message'] ?? null)) {
            $message .= ($message !== '' ? "\n\n" : '').$data['con_message'];
        }

        $resumePath = $request->hasFile('con_resume')
            ? $this->storeUpload($request->file('con_resume'), 'resumes')
            : null;

        $lead = Lead::create([
            'name' => $data['con_name'],
            'email' => $data['con_email'],
            'phone' => $data['con_phone'],
            'subject' => filled($data['con_position'] ?? null) ? 'Application: '.$data['con_position'] : 'Job Application',
            'message' => $message !== '' ? $message : 'Job application submitted from the careers page.',
            'attachment' => $resumePath,
            'source' => 'career',
        ]);

        LeadNotificationMail::dispatchFor($lead);

        return back()->with('career_status', 'Application submitted! We will be in touch shortly.');
    }
}
