@extends('layouts.admin')

@section('title', 'Daily Work')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="calendar" class="h-5 w-5 text-fuchsia-600" />
            {{ $canSeeTeam ? 'Team Daily Work' : 'My Daily Work' }}
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $updates->total() }}</span>
        </h2>
        <a href="{{ route('admin.portal.daily-work.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
            <x-admin.icon name="plus" class="h-4 w-4" /> New Update
        </a>
    </div>

    <form method="GET" action="{{ route('admin.portal.daily-work.index') }}"
        class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-6">
        <div class="lg:col-span-2">
            <label class="mb-1 block text-xs font-medium text-slate-500">Search</label>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Activity or remarks…"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        @if ($canSeeTeam)
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Employee</label>
                <select name="employee" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    <option value="">Everyone</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @selected((string) request('employee') === (string) $employee->id)>{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Category</label>
            <select name="category" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                <x-admin.icon name="search" class="h-4 w-4" /> Filter
            </button>
            <a href="{{ route('admin.portal.daily-work.index') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        @if ($canSeeTeam)
                            <th class="px-4 py-3">Employee</th>
                        @endif
                        <th class="px-4 py-3">Activity</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Time</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($updates as $update)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $update->work_date->format('d M Y') }}</td>
                            @if ($canSeeTeam)
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $update->employee?->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $update->employee?->department?->name ?? '—' }}</p>
                                </td>
                            @endif
                            <td class="px-4 py-3">
                                <p class="max-w-md text-slate-700">{{ $update->activity }}</p>
                                @if ($update->remarks)
                                    <p class="mt-0.5 max-w-md text-xs text-slate-400">{{ $update->remarks }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3"><x-portal.badge :color="$update->related_category->badgeColor()" :label="$update->related_category->label()" /></td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-500">
                                @if ($update->durationMinutes())
                                    {{ Str::substr($update->start_time, 0, 5) }}–{{ Str::substr($update->end_time, 0, 5) }}
                                    <span class="text-slate-400">({{ round($update->durationMinutes() / 60, 1) }}h)</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3"><x-portal.badge :color="$update->status->badgeColor()" :label="$update->status->label()" /></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', $update)
                                        <x-admin.edit-link :href="route('admin.portal.daily-work.edit', $update)" />
                                    @endcan
                                    @can('delete', $update)
                                        <x-admin.delete-button :action="route('admin.portal.daily-work.destroy', $update)" confirm="Delete this update?" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canSeeTeam ? 7 : 6 }}"><x-portal.empty-state message="No work logged for these filters." icon="calendar" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $updates->links() }}</div>
@endsection
