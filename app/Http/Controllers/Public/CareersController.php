<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CareersController extends Controller
{
    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'con_name' => ['required', 'string', 'max:255'],
            'con_email' => ['required', 'email', 'max:255'],
            'con_phone' => ['required', 'string', 'max:20'],
            'con_message' => ['nullable', 'string', 'max:5000'],
        ]);

        Lead::create([
            'name' => $data['con_name'],
            'email' => $data['con_email'],
            'phone' => $data['con_phone'],
            'message' => $data['con_message'] ?? null,
            'source' => 'career',
        ]);

        return back()->with('status', 'Application submitted! We will be in touch shortly.');
    }
}
