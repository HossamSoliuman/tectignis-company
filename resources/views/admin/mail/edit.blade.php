@extends('layouts.admin')

@section('title', 'Email & Forms')

@section('content')
    <div class="mb-5 flex items-center gap-2">
        <x-admin.icon name="envelope" class="h-5 w-5 text-fuchsia-600" />
        <h2 class="text-lg font-semibold text-slate-900">Email &amp; Forms</h2>
    </div>

    @if (session('mail_test_error'))
        <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <x-admin.icon name="default" class="h-5 w-5 shrink-0 text-red-500" />
            {{ session('mail_test_error') }}
        </div>
    @endif

    @php
        $smtpFields = [
            'smtp_host' => ['label' => 'SMTP Host', 'type' => 'text', 'placeholder' => 'smtp.example.com'],
            'smtp_port' => ['label' => 'SMTP Port', 'type' => 'number', 'placeholder' => '587'],
            'smtp_username' => ['label' => 'Username', 'type' => 'text', 'placeholder' => 'you@example.com'],
            'smtp_password' => ['label' => 'Password', 'type' => 'password', 'placeholder' => '••••••••'],
        ];
        $recipientFields = [
            'mail_to_default' => 'Default recipient',
            'mail_to_contact' => 'Contact form',
            'mail_to_consultation' => 'Consultation requests',
            'mail_to_career' => 'Job applications',
            'mail_to_newsletter' => 'Newsletter sign-ups',
        ];
    @endphp

    <form action="{{ route('admin.mail.update') }}" method="POST" class="max-w-3xl">
        @csrf
        @method('PUT')

        {{-- SMTP server --}}
        <div class="mb-8">
            <div class="mb-3">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                        <x-admin.icon name="cog" class="h-4 w-4" />
                    </span>
                    Outgoing Mail Server (SMTP)
                </h3>
                <p class="mt-1 pl-9 text-xs text-slate-400">When a host is set, these settings override the default mail configuration. Save before sending a test email.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-2">
                @foreach ($smtpFields as $key => $field)
                    <div>
                        <label for="{{ $key }}" class="mb-1 block text-sm font-medium text-slate-700">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" id="{{ $key }}" name="{{ $key }}"
                            value="{{ old($key, $smtp[$key]) }}" placeholder="{{ $field['placeholder'] }}"
                            @if ($field['type'] === 'password') autocomplete="new-password" @endif
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    </div>
                @endforeach
                <div>
                    <label for="smtp_encryption" class="mb-1 block text-sm font-medium text-slate-700">Encryption</label>
                    <select id="smtp_encryption" name="smtp_encryption"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        @foreach (['' => 'None', 'tls' => 'TLS (STARTTLS, port 587)', 'ssl' => 'SSL (port 465)'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('smtp_encryption', $smtp['smtp_encryption']) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="smtp_from_address" class="mb-1 block text-sm font-medium text-slate-700">From Address</label>
                    <input type="email" id="smtp_from_address" name="smtp_from_address"
                        value="{{ old('smtp_from_address', $smtp['smtp_from_address']) }}" placeholder="no-reply@example.com"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
                <div class="sm:col-span-2">
                    <label for="smtp_from_name" class="mb-1 block text-sm font-medium text-slate-700">From Name</label>
                    <input type="text" id="smtp_from_name" name="smtp_from_name"
                        value="{{ old('smtp_from_name', $smtp['smtp_from_name']) }}" placeholder="{{ config('app.name') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
            </div>
        </div>

        {{-- Form recipients --}}
        <div class="mb-8">
            <div class="mb-3">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                        <x-admin.icon name="inbox" class="h-4 w-4" />
                    </span>
                    Form Notification Recipients
                </h3>
                <p class="mt-1 pl-9 text-xs text-slate-400">Where each form's submissions are emailed. Leave a form blank to use the default recipient{{ $siteEmail ? ' (falls back to '.$siteEmail.')' : '' }}.</p>
            </div>
            <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-4">
                @foreach ($recipientFields as $key => $label)
                    <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:gap-4">
                        <label for="{{ $key }}" class="text-sm font-medium text-slate-700 sm:w-48 sm:shrink-0">{{ $label }}</label>
                        <input type="email" id="{{ $key }}" name="{{ $key }}"
                            value="{{ old($key, $recipients[$key]) }}"
                            placeholder="{{ $key === 'mail_to_default' ? ($siteEmail ?: 'name@example.com') : 'Use default recipient' }}"
                            class="w-full flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sticky save bar --}}
        <div class="sticky bottom-0 z-10 -mx-4 border-t border-slate-200 bg-white/90 px-4 py-3 backdrop-blur lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-end gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    <x-admin.icon name="check-circle" class="h-4 w-4" />
                    Save Mail Settings
                </button>
            </div>
        </div>
    </form>

    {{-- Test email (separate form) --}}
    <div class="mt-4 max-w-3xl">
        <div class="mb-3">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                    <x-admin.icon name="envelope" class="h-4 w-4" />
                </span>
                Send a Test Email
            </h3>
            <p class="mt-1 pl-9 text-xs text-slate-400">Verify your configuration by sending a test message. Save your settings above first.</p>
        </div>
        <form action="{{ route('admin.mail.test') }}" method="POST"
            class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-end">
            @csrf
            <div class="flex-1">
                <label for="test_email" class="mb-1 block text-sm font-medium text-slate-700">Send to</label>
                <input type="email" id="test_email" name="test_email" required
                    value="{{ old('test_email', $siteEmail) }}" placeholder="name@example.com"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
            </div>
            <button type="submit"
                class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-800 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-900">
                <x-admin.icon name="envelope" class="h-4 w-4" />
                Send Test
            </button>
        </form>
    </div>
@endsection
