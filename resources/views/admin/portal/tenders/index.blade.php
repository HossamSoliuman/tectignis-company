@extends('layouts.admin')

@section('title', 'Tenders')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="briefcase" class="h-5 w-5 text-fuchsia-600" />
            Tenders
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $tenders->total() }}</span>
        </h2>
        @can('create', \App\Models\Portal\Tender::class)
            <a href="{{ route('admin.portal.tenders.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Tender
            </a>
        @endcan
    </div>

    <form method="GET" action="{{ route('admin.portal.tenders.index') }}"
        class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label class="mb-1 block text-xs font-medium text-slate-500">Search</label>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Code, tender no., title or customer…"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Stage</label>
            <select name="stage" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($stages as $value => $label)
                    <option value="{{ $value }}" @selected(request('stage') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Decision</label>
            <select name="decision" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($decisions as $value => $label)
                    <option value="{{ $value }}" @selected(request('decision') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Portal</label>
            <select name="portal" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($sources as $value => $label)
                    <option value="{{ $value }}" @selected(request('portal') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Owner</label>
            <select name="owner" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Anyone</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) request('owner') === (string) $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Deadline from</label>
            <input type="date" name="deadline_from" value="{{ request('deadline_from') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Deadline to</label>
            <input type="date" name="deadline_to" value="{{ request('deadline_to') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div class="flex flex-wrap items-center gap-4 sm:col-span-2 lg:col-span-2">
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="hidden" name="closing_soon" value="0">
                <input type="checkbox" name="closing_soon" value="1" @checked(request()->boolean('closing_soon')) class="rounded border-slate-300">
                Closing soon
            </label>
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" @checked(request()->boolean('active')) class="rounded border-slate-300">
                Active only
            </label>
        </div>
        <div class="flex items-end gap-2 sm:col-span-2">
            <button type="submit" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                <x-admin.icon name="search" class="h-4 w-4" /> Filter
            </button>
            <a href="{{ route('admin.portal.tenders.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Tender</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Owner</th>
                        <th class="px-4 py-3">Stage</th>
                        <th class="px-4 py-3">Eligibility</th>
                        <th class="px-4 py-3">Readiness</th>
                        <th class="px-4 py-3">Deadline</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tenders as $tender)
                        <tr class="transition hover:bg-slate-50 {{ $tender->isDeadlinePassed() ? 'bg-rose-50/40' : ($tender->isClosingSoon() ? 'bg-amber-50/40' : '') }}">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.portal.tenders.show', $tender) }}" class="font-medium text-slate-800 hover:text-fuchsia-700">{{ $tender->title }}</a>
                                <p class="text-xs text-slate-400">{{ $tender->code }} · {{ $tender->tender_number }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $tender->customer_organization ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $tender->owner?->name ?? 'Unassigned' }}</td>
                            <td class="px-4 py-3"><x-portal.badge :color="$tender->stage->badgeColor()" :label="$tender->stage->label()" /></td>
                            <td class="px-4 py-3"><x-portal.badge :color="$tender->eligibility_status->badgeColor()" :label="$tender->eligibility_status->label()" /></td>
                            <td class="w-32 px-4 py-3"><x-portal.progress-bar :value="$tender->completion_percent" /></td>
                            <td class="px-4 py-3"><x-portal.due-date :date="$tender->submission_deadline_at" :closed="$tender->stage->isSubmittedOrLater()" /></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', $tender)
                                        <x-admin.edit-link :href="route('admin.portal.tenders.edit', $tender)" />
                                    @endcan
                                    @can('delete', $tender)
                                        <x-admin.delete-button :action="route('admin.portal.tenders.destroy', $tender)" confirm="Delete this tender and its whole workspace?" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"><x-portal.empty-state message="No tenders match these filters." icon="briefcase" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $tenders->links() }}</div>
@endsection
