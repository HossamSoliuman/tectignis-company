@extends('layouts.admin')

@section('title', 'Lead '.$lead->reference())

@section('content')
    @php
        $canUpdate = auth()->user()->can('update', $lead);
        $timezone = config('app.timezone');
        $details = [
            ['envelope', 'Email', $lead->email, $lead->email ? 'mailto:'.$lead->email : null],
            ['phone', 'Phone / WhatsApp', $lead->phone, $lead->phone ? 'tel:'.preg_replace('/[^+\d]/', '', $lead->phone) : null],
            ['office', 'Company', $lead->company, null],
            ['globe', 'Country', $lead->country, null],
            ['briefcase', 'Service', $lead->service, null],
            ['document-text', 'Subject', $lead->subject, null],
            ['chart-bar', 'Budget', $lead->budgetLabel(), null],
            ['clock', 'Timeline', $lead->timelineLabel(), null],
            ['inbox', 'Source', $lead->sourceLabel(), null],
            ['calendar', 'Received', $lead->created_at->timezone($timezone)->format('M d, Y H:i T'), null],
            ['shield-check', 'Consent', $lead->consented_at?->timezone($timezone)->format('M d, Y H:i T'), null],
        ];
        $attribution = array_filter([
            'Landing page' => $lead->page_url,
            'UTM source' => $lead->utm_source,
            'UTM medium' => $lead->utm_medium,
            'UTM campaign' => $lead->utm_campaign,
            'UTM term' => $lead->utm_term,
            'UTM content' => $lead->utm_content,
        ]);
        $input = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    @endphp

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Leads
        </a>
        @if ($lead->trashed())
            <div class="flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                This lead is in the trash.
                @can('restore', $lead)
                    <form action="{{ route('admin.leads.restore', $lead) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="font-semibold underline">Restore</button>
                    </form>
                @endcan
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        {{-- Submitted information --}}
        <div class="space-y-5 xl:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-3 border-b border-slate-100 pb-5">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-fuchsia-500 to-purple-600 text-lg font-semibold text-white">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($lead->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-semibold text-slate-900">{{ $lead->name }}</h2>
                        <p class="text-xs text-slate-400">{{ $lead->reference() }}{{ $lead->company ? ' · '.$lead->company : '' }}</p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $lead->status->badgeClasses() }}">{{ $lead->status->label() }}</span>
                </div>

                <dl class="grid grid-cols-1 gap-4 pt-5 text-sm sm:grid-cols-2">
                    @foreach ($details as [$icon, $label, $value, $href])
                        <div class="flex items-start gap-2">
                            <x-admin.icon :name="$icon" class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" />
                            <div class="min-w-0">
                                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</dt>
                                <dd class="break-words text-slate-700">
                                    @if ($value && $href)
                                        <a href="{{ $href }}" class="text-fuchsia-600 hover:underline">{{ $value }}</a>
                                    @else
                                        {{ $value ?: '—' }}
                                    @endif
                                </dd>
                            </div>
                        </div>
                    @endforeach
                </dl>

                @if ($lead->message)
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $lead->sourceEnum()?->isEnquiry() ? 'Project description' : 'Message' }}</span>
                        <p class="whitespace-pre-wrap rounded-lg bg-slate-50 p-4 text-sm text-slate-700">{{ $lead->message }}</p>
                    </div>
                @endif

                @if ($lead->attachment)
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <span class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-400">Attachment</span>
                        <a href="{{ asset('uploads/'.$lead->attachment) }}" target="_blank"
                            class="inline-flex items-center gap-2 rounded-lg bg-slate-50 px-4 py-2.5 text-sm font-medium text-fuchsia-600 transition hover:bg-slate-100">
                            <x-admin.icon name="download" class="h-4 w-4" /> {{ basename($lead->attachment) }}
                        </a>
                    </div>
                @endif

                <div class="mt-5 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
                    @if ($lead->email)
                        <a href="mailto:{{ $lead->email }}?subject={{ rawurlencode('Re: Your enquiry'.($lead->service ? ' about '.$lead->service : '')) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                            <x-admin.icon name="envelope" class="h-4 w-4" /> Reply via Email
                        </a>
                    @endif
                    @if (! $lead->trashed())
                        @can('delete', $lead)
                            <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Move this lead to the trash?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                                    <x-admin.icon name="trash" class="h-4 w-4" /> Delete
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>

            {{-- Attribution --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <x-admin.icon name="trending-up" class="h-4 w-4 text-fuchsia-600" /> Attribution
                </h3>
                @if ($attribution)
                    <dl class="divide-y divide-slate-100 text-sm">
                        @foreach ($attribution as $label => $value)
                            <div class="flex flex-col gap-1 py-2 sm:flex-row sm:gap-4">
                                <dt class="w-36 shrink-0 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</dt>
                                <dd class="min-w-0 break-all text-slate-700">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-sm text-slate-400">No landing page or campaign data was captured for this lead.</p>
                @endif
            </div>

            {{-- Internal notes --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <x-admin.icon name="chat-alt" class="h-4 w-4 text-fuchsia-600" /> Internal notes
                    <span class="text-xs font-normal text-slate-400">Private — never shared with the visitor</span>
                </h3>

                @if ($canUpdate && ! $lead->trashed())
                    <form action="{{ route('admin.leads.notes.store', $lead) }}" method="POST" class="mb-4">
                        @csrf
                        <label for="note-body" class="sr-only">Add a note</label>
                        <textarea id="note-body" name="body" rows="3" maxlength="5000" required placeholder="Add a note — call summary, next step, requirements…"
                            class="{{ $input }}">{{ old('body') }}</textarea>
                        <div class="mt-2 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                                <x-admin.icon name="plus" class="h-4 w-4" /> Add note
                            </button>
                        </div>
                    </form>
                @endif

                <ul class="space-y-3">
                    @forelse ($lead->notes as $note)
                        <li class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                            <p class="whitespace-pre-wrap text-sm text-slate-700">{{ $note->body }}</p>
                            <p class="mt-1.5 text-xs text-slate-400">
                                {{ $note->author?->name ?? 'Deleted user' }} · {{ $note->created_at->timezone($timezone)->format('M d, Y H:i') }}
                            </p>
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">No notes yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Pipeline controls + audit trail --}}
        <div class="space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <x-admin.icon name="clipboard-list" class="h-4 w-4 text-fuchsia-600" /> Pipeline
                </h3>
                @if ($canUpdate && ! $lead->trashed())
                    <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="lead-status" class="mb-1 block text-xs font-medium text-slate-500">Status</label>
                            <select id="lead-status" name="status" class="{{ $input }}">
                                @foreach (\App\Enums\LeadStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status', $lead->status->value) === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="lead-assignee" class="mb-1 block text-xs font-medium text-slate-500">Assigned to</label>
                            <select id="lead-assignee" name="assigned_to" class="{{ $input }}">
                                <option value="">Unassigned</option>
                                @foreach ($assignees as $assignee)
                                    <option value="{{ $assignee->id }}" @selected((string) old('assigned_to', $lead->assigned_to) === (string) $assignee->id)>{{ $assignee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                            <x-admin.icon name="check-circle" class="h-4 w-4" /> Save changes
                        </button>
                    </form>
                @else
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-xs font-medium text-slate-500">Status</dt>
                            <dd class="mt-0.5"><span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $lead->status->badgeClasses() }}">{{ $lead->status->label() }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-500">Assigned to</dt>
                            <dd class="mt-0.5 text-slate-700">{{ $lead->assignee?->name ?? 'Unassigned' }}</dd>
                        </div>
                    </dl>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold text-slate-800">
                    <x-admin.icon name="clock" class="h-4 w-4 text-fuchsia-600" /> Activity
                </h3>
                <ol class="relative space-y-4 border-l border-slate-200 pl-4">
                    @forelse ($lead->activities as $activity)
                        <li class="relative">
                            <span class="absolute -left-[1.3rem] top-1 h-2.5 w-2.5 rounded-full bg-fuchsia-400 ring-4 ring-white"></span>
                            <p class="text-sm text-slate-700">{{ $activity->description() }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $activity->user?->name ?? ($activity->type === \App\Models\LeadActivity::CREATED ? 'Website' : 'System') }}
                                · {{ $activity->created_at->timezone($timezone)->format('M d, Y H:i') }}
                            </p>
                        </li>
                    @empty
                        <li class="text-sm text-slate-400">No activity recorded yet.</li>
                    @endforelse
                </ol>
            </div>
        </div>
    </div>
@endsection
