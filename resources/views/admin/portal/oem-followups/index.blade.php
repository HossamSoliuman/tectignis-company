@extends('layouts.admin')

@section('title', 'OEM Follow-ups')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="share" class="h-5 w-5 text-fuchsia-600" />
            OEM Follow-ups
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $followups->total() }}</span>
        </h2>
        @can('create', \App\Models\Portal\OemFollowup::class)
            <a href="{{ route('admin.portal.oem-followups.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Follow-up
            </a>
        @endcan
    </div>

    <form method="GET" action="{{ route('admin.portal.oem-followups.index') }}"
        class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label class="mb-1 block text-xs font-medium text-slate-500">Search</label>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="OEM, product or contact…"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($followupStatuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Requirement</label>
            <select name="requirement_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($requirementTypes as $value => $label)
                    <option value="{{ $value }}" @selected(request('requirement_type') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Tender</label>
            <select name="tender" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Any</option>
                @foreach ($tenders as $tender)
                    <option value="{{ $tender->id }}" @selected((string) request('tender') === (string) $tender->id)>{{ $tender->code }} — {{ Str::limit($tender->title, 30) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="hidden" name="due" value="0">
                <input type="checkbox" name="due" value="1" @checked(request()->boolean('due')) class="rounded border-slate-300">
                Due now
            </label>
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="hidden" name="open" value="0">
                <input type="checkbox" name="open" value="1" @checked(request()->boolean('open')) class="rounded border-slate-300">
                Open only
            </label>
        </div>
        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-6">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                <x-admin.icon name="search" class="h-4 w-4" /> Filter
            </button>
            <a href="{{ route('admin.portal.oem-followups.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">OEM</th>
                        <th class="px-4 py-3">Needed For</th>
                        <th class="px-4 py-3">Requirement</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Needed By</th>
                        <th class="px-4 py-3">Next Chase</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($followups as $followup)
                        <tr class="transition hover:bg-slate-50 {{ $followup->isOverdue() ? 'bg-rose-50/40' : ($followup->isChaseDue() ? 'bg-amber-50/40' : '') }}">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.portal.oem-followups.show', $followup) }}" class="font-medium text-slate-800 hover:text-fuchsia-700">{{ $followup->oem_name }}</a>
                                <p class="text-xs text-slate-400">{{ $followup->product ?? $followup->contact_person ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-500">
                                @if ($followup->tender)
                                    <a href="{{ route('admin.portal.tenders.oem.index', $followup->tender) }}" class="hover:text-fuchsia-700">{{ $followup->tender->code }}</a>
                                @else
                                    <span class="text-xs text-slate-400">Standalone</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $followup->requirement_type->label() }}</td>
                            <td class="px-4 py-3"><x-portal.badge :color="$followup->status->badgeColor()" :label="$followup->status->label()" /></td>
                            <td class="px-4 py-3"><x-portal.due-date :date="$followup->required_by" :closed="$followup->status->isClosed()" /></td>
                            <td class="px-4 py-3"><x-portal.due-date :date="$followup->next_followup_at" :closed="$followup->status->isClosed()" /></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', $followup)
                                        <x-admin.edit-link :href="route('admin.portal.oem-followups.edit', $followup)" />
                                    @endcan
                                    @can('delete', $followup)
                                        <x-admin.delete-button :action="route('admin.portal.oem-followups.destroy', $followup)" confirm="Delete this follow-up and its contact history?" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"><x-portal.empty-state message="Nothing is being chased from any OEM." icon="share" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $followups->links() }}</div>
@endsection
