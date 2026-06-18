@extends('layouts.admin')

@section('title', 'My Account')

@section('content')
    <div class="mb-5 flex items-center gap-2">
        <x-admin.icon name="user" class="h-5 w-5 text-fuchsia-600" />
        <h2 class="text-lg font-semibold text-slate-900">My Account</h2>
    </div>

    <form action="{{ route('admin.account.update') }}" method="POST" class="max-w-2xl">
        @csrf
        @method('PUT')

        {{-- Profile --}}
        <div class="mb-8">
            <div class="mb-3">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                        <x-admin.icon name="user" class="h-4 w-4" />
                    </span>
                    Profile
                </h3>
                <p class="mt-1 pl-9 text-xs text-slate-400">Your name and the email address you sign in with.</p>
            </div>
            <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-4">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-8">
            <div class="mb-3">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                        <x-admin.icon name="lock-closed" class="h-4 w-4" />
                    </span>
                    Password
                </h3>
                <p class="mt-1 pl-9 text-xs text-slate-400">Leave the new password fields empty to keep your current password.</p>
            </div>
            <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-4">
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-700">New Password</label>
                    <input type="password" id="password" name="password" autocomplete="new-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
            </div>
        </div>

        {{-- Confirm with current password --}}
        <div class="mb-8">
            <div class="mb-3">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                        <x-admin.icon name="shield-check" class="h-4 w-4" />
                    </span>
                    Confirm Changes
                </h3>
                <p class="mt-1 pl-9 text-xs text-slate-400">Enter your current password to save any of the changes above.</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4">
                <div>
                    <label for="current_password" class="mb-1 block text-sm font-medium text-slate-700">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                </div>
            </div>
        </div>

        {{-- Sticky save bar --}}
        <div class="sticky bottom-0 z-10 -mx-4 border-t border-slate-200 bg-white/90 px-4 py-3 backdrop-blur lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-end gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    <x-admin.icon name="check-circle" class="h-4 w-4" />
                    Save Account
                </button>
            </div>
        </div>
    </form>
@endsection
