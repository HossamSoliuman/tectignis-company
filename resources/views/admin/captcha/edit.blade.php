@extends('layouts.admin')

@section('title', 'CAPTCHA')

@section('content')
    @php
        $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="shield-check" class="h-5 w-5 text-fuchsia-600" />
            CAPTCHA Configuration
        </h2>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $configured ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $configured ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                {{ $configured ? 'Configured' : 'Not configured' }}
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $active ? 'bg-fuchsia-100 text-fuchsia-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $active ? 'Protecting public forms' : 'Off — forms are not protected' }}
            </span>
        </div>
    </div>

    @if (session('captcha_test_error'))
        <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <x-admin.icon name="default" class="h-5 w-5 shrink-0 text-red-500" />
            {{ session('captcha_test_error') }}
        </div>
    @endif

    <form action="{{ route('admin.captcha.update') }}" method="POST" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
            <div>
                <span class="mb-1 block text-sm font-medium text-slate-700">Provider</span>
                <p class="rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-600">Google reCAPTCHA v2 — "I'm not a robot" checkbox</p>
                <p class="mt-1 text-xs text-slate-400">Create v2 Checkbox keys in the Google reCAPTCHA admin console and add this site's domain to the key.</p>
            </div>

            <div>
                <label for="site_key" class="mb-1 block text-sm font-medium text-slate-700">Site key</label>
                <input type="text" id="site_key" name="site_key" value="{{ old('site_key', $siteKey) }}" autocomplete="off" class="{{ $input }}">
                <p class="mt-1 text-xs text-slate-400">Public — rendered on the forms.</p>
            </div>

            <div>
                <label for="secret_key" class="mb-1 block text-sm font-medium text-slate-700">Secret key</label>
                <input type="password" id="secret_key" name="secret_key" autocomplete="new-password"
                    placeholder="{{ $maskedSecret ? 'Saved ('.$maskedSecret.') — leave blank to keep' : 'Paste the secret key' }}" class="{{ $input }}">
                <p class="mt-1 text-xs text-slate-400">Stored encrypted and never shown again after saving. Enter a new value only to replace it.</p>
            </div>

            <div class="flex items-start gap-2 border-t border-slate-100 pt-5">
                <input type="hidden" name="enabled" value="0">
                <input type="checkbox" id="enabled" name="enabled" value="1" class="mt-1 rounded border-slate-300" @checked(old('enabled', $enabled))>
                <label for="enabled" class="text-sm text-slate-700">
                    <span class="font-medium">Enable CAPTCHA on public forms</span>
                    <span class="block text-xs text-slate-500">Contact, consultation/quote, service enquiry, careers, newsletter and download forms. Submissions that fail the check are rejected and no lead is created.</span>
                </label>
            </div>

            @if ($updatedAt)
                <p class="text-xs text-slate-400">Last changed {{ $updatedAt->format('M d, Y H:i T') }}{{ $updatedBy ? ' by '.$updatedBy : '' }}.</p>
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="check-circle" class="h-4 w-4" /> Save CAPTCHA settings
            </button>
        </div>
    </form>

    {{-- Test configuration --}}
    <div class="mt-8 max-w-3xl">
        <h3 class="mb-1 flex items-center gap-2 text-sm font-semibold text-slate-800">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                <x-admin.icon name="check-circle" class="h-4 w-4" />
            </span>
            Test configuration
        </h3>
        <p class="mb-3 pl-9 text-xs text-slate-400">Save first. Tick the checkbox and run the test to verify both keys end to end; without ticking it, only the secret key is checked.</p>
        <form action="{{ route('admin.captcha.test') }}" method="POST" class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
            @csrf
            @if ($siteKey)
                <div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
            @else
                <p class="text-sm text-slate-400">Save a site key to show the test checkbox.</p>
            @endif
            <button type="submit" @disabled(! $configured)
                class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-800 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-50">
                Run test
            </button>
        </form>
    </div>

    @if ($siteKey)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection
