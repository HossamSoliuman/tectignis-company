@extends('layouts.admin')

@section('title', 'Lead: '.$lead->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.leads.index') }}" class="text-sm text-fuchsia-600 hover:underline">← Back to Leads</a>
    </div>

    <div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Name</span>
                <span class="font-medium">{{ $lead->name }}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Email</span>
                <a href="mailto:{{ $lead->email }}" class="text-fuchsia-600">{{ $lead->email }}</a>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Phone</span>
                <span>{{ $lead->phone ?? '—' }}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Source</span>
                <span>{{ $lead->source ?? 'contact' }}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Subject</span>
                <span>{{ $lead->subject ?? '—' }}</span>
            </div>
            <div>
                <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Date</span>
                <span>{{ $lead->created_at->format('M d, Y H:i') }}</span>
            </div>
        </div>

        @if ($lead->message)
            <div class="border-t border-slate-100 pt-4">
                <span class="block text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Message</span>
                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $lead->message }}</p>
            </div>
        @endif

        <div class="flex items-center gap-3 border-t border-slate-100 pt-4">
            <a href="mailto:{{ $lead->email }}"
                class="rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">
                Reply via Email
            </a>
            <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                onsubmit="return confirm('Delete this lead?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection
