@extends('layouts.admin')

@section('title', $followup->oem_name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.oem-followups.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to follow-ups
        </a>
    </div>

    <div class="mb-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $followup->requirement_type->label() }}</p>
                <h2 class="text-lg font-semibold text-slate-900">{{ $followup->oem_name }}</h2>
                <p class="mt-0.5 text-sm text-slate-500">
                    {{ $followup->product ?? 'No product recorded' }}
                    @if ($followup->tender)
                        · for <a href="{{ route('admin.portal.tenders.oem.index', $followup->tender) }}" class="text-fuchsia-600 hover:underline">{{ $followup->tender->code }} {{ $followup->tender->title }}</a>
                    @endif
                </p>
            </div>
            <div class="flex shrink-0 items-center gap-1">
                @can('update', $followup)
                    <x-admin.edit-link :href="route('admin.portal.oem-followups.edit', $followup)" />
                @endcan
                @can('delete', $followup)
                    <x-admin.delete-button :action="route('admin.portal.oem-followups.destroy', $followup)" confirm="Delete this follow-up and its contact history?" />
                @endcan
            </div>
        </div>

        <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 text-sm sm:grid-cols-4">
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Status</dt>
                <dd class="mt-0.5"><x-portal.badge :color="$followup->status->badgeColor()" :label="$followup->status->label()" /></dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Requested</dt>
                <dd class="mt-0.5 text-slate-700">{{ $followup->requested_on?->format('d M Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Needed By</dt>
                <dd class="mt-0.5"><x-portal.due-date :date="$followup->required_by" :closed="$followup->status->isClosed()" /></dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Next Chase</dt>
                <dd class="mt-0.5"><x-portal.due-date :date="$followup->next_followup_at" :closed="$followup->status->isClosed()" /></dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Contact</dt>
                <dd class="mt-0.5 text-slate-700">{{ $followup->contact_person ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Channel</dt>
                <dd class="mt-0.5 truncate text-slate-700">{{ $followup->contact_channel ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Raised By</dt>
                <dd class="mt-0.5 text-slate-700">{{ $followup->requester?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-wide text-slate-400">Contacts Logged</dt>
                <dd class="mt-0.5 font-medium text-slate-800">{{ $followup->followupUpdates->count() }}×</dd>
            </div>
        </dl>

        @if ($followup->remarks)
            <p class="mt-4 whitespace-pre-line border-t border-slate-100 pt-4 text-sm text-slate-600">{{ $followup->remarks }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            @can('update', $followup)
                <x-portal.panel title="Log a Contact" icon="phone">
                    <form action="{{ route('admin.portal.oem-followups.updates.store', $followup) }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">What happened? *</label>
                            <textarea name="note" rows="3" required placeholder="Who was called, what they said, what they promised…"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400"></textarea>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Status now</label>
                                <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                    @foreach ($followupStatuses as $value => $label)
                                        <option value="{{ $value }}" @selected($followup->status->value === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Contacted on</label>
                                <input type="date" name="contacted_on" value="{{ now()->toDateString() }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Chase again on</label>
                                <input type="date" name="next_followup_at"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Attachment</label>
                                <input type="file" name="attachment"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-sm file:mr-2 file:rounded file:border-0 file:bg-slate-100 file:px-2 file:py-1 file:text-xs file:font-medium focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Log Contact</button>
                        </div>
                    </form>
                </x-portal.panel>
            @endcan

            <x-portal.panel title="Contact History" icon="clock" :count="$followup->followupUpdates->count()">
                <ol class="divide-y divide-slate-100">
                    @forelse ($followup->followupUpdates as $update)
                        <li class="px-4 py-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-medium text-slate-800">{{ $update->employee?->name ?? 'System' }}</p>
                                <span class="text-xs text-slate-400">{{ ($update->contacted_on ?? $update->created_at)?->format('d M Y, g:i A') }}</span>
                            </div>
                            <p class="mt-1 whitespace-pre-line text-sm text-slate-600">{{ $update->note }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                @if ($update->status_from && $update->status_to && $update->status_from !== $update->status_to)
                                    <span class="text-slate-400">{{ Str::headline($update->status_from) }}</span>
                                    <span class="text-slate-300">→</span>
                                    <span class="font-medium text-slate-600">{{ Str::headline($update->status_to) }}</span>
                                @endif
                                @if ($update->next_followup_at)
                                    <span class="text-slate-400">· next chase {{ $update->next_followup_at->format('d M Y') }}</span>
                                @endif
                                @foreach ($update->attachments as $attachment)
                                    <a href="{{ route('admin.portal.files.show', $attachment) }}" class="inline-flex items-center gap-1 text-fuchsia-600 hover:underline">
                                        <x-admin.icon name="paper-clip" class="h-3.5 w-3.5" /> {{ $attachment->original_name }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="Nobody has contacted this OEM yet." icon="phone" /></li>
                    @endforelse
                </ol>
            </x-portal.panel>
        </div>

        <div class="space-y-5">
            <x-portal.panel title="Attachments" icon="paper-clip" :count="$followup->attachments->count()">
                <ul class="divide-y divide-slate-100">
                    @forelse ($followup->attachments as $attachment)
                        <li class="flex items-center justify-between gap-3 px-4 py-3">
                            <a href="{{ route('admin.portal.files.show', $attachment) }}" class="min-w-0 truncate text-sm font-medium text-slate-700 hover:text-fuchsia-700">
                                {{ $attachment->original_name }}
                            </a>
                            <span class="shrink-0 text-xs text-slate-400">{{ $attachment->humanSize() }}</span>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="No documents attached." icon="paper-clip" /></li>
                    @endforelse
                </ul>
            </x-portal.panel>
        </div>
    </div>
@endsection
