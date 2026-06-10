@extends('layouts.admin')

@section('title', 'Lead: '.$lead->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Leads
        </a>
    </div>

    <div class="max-w-2xl space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-5">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-lg font-semibold text-white">
                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($lead->name, 0, 1)) }}
            </span>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ $lead->name }}</h2>
                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $lead->source ?? 'contact' }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div class="flex items-start gap-2">
                <x-admin.icon name="envelope" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400">Email</span>
                    <a href="mailto:{{ $lead->email }}" class="text-fuchsia-600 hover:underline">{{ $lead->email }}</a>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <x-admin.icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400">Phone</span>
                    <span class="text-slate-700">{{ $lead->phone ?? '—' }}</span>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <x-admin.icon name="document-text" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400">Subject</span>
                    <span class="text-slate-700">{{ $lead->subject ?? '—' }}</span>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <x-admin.icon name="calendar" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400">Date</span>
                    <span class="text-slate-700">{{ $lead->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        @if ($lead->message)
            <div class="border-t border-slate-100 pt-4">
                <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">Message</span>
                <p class="whitespace-pre-wrap rounded-lg bg-slate-50 p-4 text-sm text-slate-700">{{ $lead->message }}</p>
            </div>
        @endif

        @if ($lead->attachment)
            <div class="border-t border-slate-100 pt-4">
                <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">Attachment</span>
                <a href="{{ asset('uploads/'.$lead->attachment) }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-50 px-4 py-2.5 text-sm font-medium text-fuchsia-600 transition hover:bg-slate-100">
                    <x-admin.icon name="download" class="h-4 w-4" /> {{ basename($lead->attachment) }}
                </a>
            </div>
        @endif

        <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
            <a href="mailto:{{ $lead->email }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="envelope" class="h-4 w-4" /> Reply via Email
            </a>
            <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                onsubmit="return confirm('Delete this lead?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                    <x-admin.icon name="trash" class="h-4 w-4" /> Delete
                </button>
            </form>
        </div>
    </div>
@endsection
