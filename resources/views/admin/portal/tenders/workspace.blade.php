@extends('layouts.admin')

@section('title', $tender->code)

@php
    // Every tab shares this bar. Eleven copies of the same header is eleven
    // chances for them to drift apart, so it lives here once.
    $tabs = [
        ['overview', 'Overview', route('admin.portal.tenders.show', $tender)],
        ['eligibility', 'Eligibility', route('admin.portal.tenders.eligibility.index', $tender)],
        ['documents', 'Documents', route('admin.portal.tenders.documents.index', $tender)],
        ['oem', 'OEM Follow-up', route('admin.portal.tenders.oem.index', $tender)],
        ['tasks', 'Tasks', route('admin.portal.tenders.tasks.index', $tender)],
        ['clarifications', 'Clarifications', route('admin.portal.tenders.clarifications.index', $tender)],
        ['technical', 'Technical', route('admin.portal.tenders.technical', $tender)],
        ['commercial', 'Commercial', route('admin.portal.tenders.commercial', $tender)],
        ['submission', 'Submission', route('admin.portal.tenders.submission.index', $tender)],
        ['result', 'Result', route('admin.portal.tenders.result.index', $tender)],
        ['activity', 'Activity Log', route('admin.portal.tenders.activity.index', $tender)],
    ];

    $countdown = $tender->countdown();
    $deadlinePassed = $tender->isDeadlinePassed();
@endphp

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.tenders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to tenders
        </a>
    </div>

    <div class="mb-5 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4 p-5">
            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    {{ $tender->code }} · {{ $tender->tender_number }}
                    @if ($tender->tender_url)
                        <a href="{{ $tender->tender_url }}" target="_blank" rel="noopener"
                            class="ml-1 inline-flex items-center gap-1 text-fuchsia-600 normal-case hover:underline">
                            <x-admin.icon name="external-link" class="h-3.5 w-3.5" /> portal
                        </a>
                    @endif
                </p>
                <h2 class="text-lg font-semibold text-slate-900">{{ $tender->title }}</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ $tender->customer_organization ?? 'Customer not recorded' }}</p>

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <x-portal.badge :color="$tender->stage->badgeColor()" :label="$tender->stage->label()" />
                    <x-portal.badge :color="$tender->decision->badgeColor()" :label="$tender->decision->label()" />
                    <x-portal.badge :color="$tender->eligibility_status->badgeColor()" :label="$tender->eligibility_status->label()" />
                    <x-portal.badge :color="$tender->portal->badgeColor()" :label="$tender->portal->label()" />
                </div>
            </div>

            <div class="flex shrink-0 flex-col items-end gap-3">
                <div class="flex items-center gap-1">
                    @can('update', $tender)
                        <x-admin.edit-link :href="route('admin.portal.tenders.edit', $tender)" />
                    @endcan
                    @can('delete', $tender)
                        <x-admin.delete-button :action="route('admin.portal.tenders.destroy', $tender)" confirm="Delete this tender and its whole workspace?" />
                    @endcan
                </div>

                {{-- The number everyone asks for first: how long is left. --}}
                <div class="rounded-lg px-4 py-2 text-right {{ $deadlinePassed ? 'bg-rose-50 text-rose-700' : ($tender->isClosingSoon() ? 'bg-amber-50 text-amber-700' : 'bg-slate-50 text-slate-700') }}">
                    <p class="text-[10px] font-semibold uppercase tracking-widest opacity-70">Submission deadline</p>
                    <p class="text-sm font-semibold">{{ $tender->submission_deadline_at?->format('d M Y, g:i A') ?? 'Not set' }}</p>
                    <p class="text-xs">
                        @if ($deadlinePassed)
                            Deadline passed
                        @elseif ($countdown)
                            {{ $countdown }} left
                        @elseif ($tender->stage->isSubmittedOrLater())
                            Submitted
                        @else
                            &mdash;
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 border-t border-slate-100 px-5 py-4 text-sm sm:grid-cols-4">
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Bid Owner</dt>
                <dd class="mt-0.5 font-medium text-slate-800">{{ $tender->owner?->name ?? 'Unassigned' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Technical</dt>
                <dd class="mt-0.5 font-medium text-slate-800">{{ $tender->technicalOwner?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Commercial</dt>
                <dd class="mt-0.5 font-medium text-slate-800">{{ $tender->salesOwner?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Readiness</dt>
                <dd class="mt-1"><x-portal.progress-bar :value="$tender->completion_percent" /></dd>
            </div>
        </div>

        <nav class="flex gap-1 overflow-x-auto border-t border-slate-100 px-3 py-2">
            @foreach ($tabs as [$key, $label, $url])
                <a href="{{ $url }}"
                    class="whitespace-nowrap rounded-lg px-3 py-1.5 text-sm font-medium transition {{ $tab === $key ? 'bg-fuchsia-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    @yield('tab')
@endsection
