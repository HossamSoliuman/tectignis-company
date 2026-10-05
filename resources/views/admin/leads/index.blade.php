@extends('layouts.admin')

@section('title', 'Leads')

@section('content')
    @php
        $canExport = auth()->user()->can('export', \App\Models\Lead::class);
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $activeStatus = $filters['status'] ?? null;
        $totalAll = $statusCounts->sum();
        $hasFilters = collect(request()->except(['page', 'per_page', 'sort', 'direction', 'status', 'trashed']))->filter(fn ($v) => filled($v))->isNotEmpty();

        // Sorting links keep every other parameter (spec §28.4).
        $sortUrl = function (string $column) {
            $isCurrent = request('sort', 'created_at') === $column;
            $direction = $isCurrent && request('direction', 'desc') === 'desc' ? 'asc' : 'desc';

            return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $direction, 'page' => null]);
        };
        $sortIcon = fn (string $column): string => request('sort', 'created_at') === $column ? (request('direction', 'desc') === 'desc' ? '↓' : '↑') : '';
        $input = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="inbox" class="h-5 w-5 text-fuchsia-600" />
            {{ $trashed ? 'Trashed Leads' : 'Leads' }}
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $leads->total() }}</span>
        </h2>
        <div class="flex flex-wrap items-center gap-2">
            <x-admin.search-form placeholder="Name, company, email, phone, ID…" />
            @if ($canExport)
                <a href="{{ route('admin.leads.export', request()->except(['page', 'per_page'])) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700">
                    <x-admin.icon name="download" class="h-4 w-4" /> Export CSV
                </a>
            @endif
            @if ($isSuperAdmin)
                <a href="{{ $trashed ? route('admin.leads.index') : route('admin.leads.index', ['trashed' => 1]) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                    <x-admin.icon name="{{ $trashed ? 'arrow-left' : 'trash' }}" class="h-4 w-4" /> {{ $trashed ? 'Back to leads' : 'Trash' }}
                </a>
            @endif
        </div>
    </div>

    {{-- Pipeline: one tab per status with live counts for the current filters --}}
    <div class="mb-4 flex gap-2 overflow-x-auto pb-1">
        <a href="{{ request()->fullUrlWithQuery(['status' => null, 'page' => null]) }}"
            class="flex shrink-0 flex-col rounded-xl border px-4 py-2.5 transition {{ $activeStatus === null ? 'border-fuchsia-300 bg-fuchsia-50 ring-1 ring-fuchsia-200' : 'border-slate-200 bg-white hover:border-slate-300' }}">
            <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">All</span>
            <span class="text-lg font-bold text-slate-900">{{ number_format($totalAll) }}</span>
        </a>
        @foreach ($statuses as $status)
            <a href="{{ request()->fullUrlWithQuery(['status' => $status->value, 'page' => null]) }}"
                class="flex shrink-0 flex-col rounded-xl border px-4 py-2.5 transition {{ $activeStatus === $status->value ? 'border-fuchsia-300 bg-fuchsia-50 ring-1 ring-fuchsia-200' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                    <span class="h-2 w-2 rounded-full {{ \Illuminate\Support\Str::before($status->badgeClasses(), ' ') }}"></span>
                    {{ $status->label() }}
                </span>
                <span class="text-lg font-bold text-slate-900">{{ number_format($statusCounts[$status->value] ?? 0) }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <details class="mb-4 rounded-xl border border-slate-200 bg-white shadow-sm" @if ($hasFilters) open @endif>
        <summary class="flex cursor-pointer items-center justify-between px-4 py-3 text-sm font-semibold text-slate-700">
            <span class="inline-flex items-center gap-2"><x-admin.icon name="search" class="h-4 w-4 text-slate-400" /> Filters</span>
            @if ($hasFilters)
                <span class="rounded-full bg-fuchsia-100 px-2 py-0.5 text-xs font-medium text-fuchsia-700">Active</span>
            @endif
        </summary>
        <form method="GET" action="{{ route('admin.leads.index') }}" class="grid grid-cols-1 gap-3 border-t border-slate-100 p-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (['q', 'status', 'sort', 'direction', 'per_page', 'trashed'] as $keep)
                @if (filled(request($keep)))
                    <input type="hidden" name="{{ $keep }}" value="{{ request($keep) }}">
                @endif
            @endforeach

            <div>
                <label for="f-service" class="mb-1 block text-xs font-medium text-slate-500">Service</label>
                <select id="f-service" name="service" class="{{ $input }}">
                    <option value="">Any service</option>
                    @foreach ($serviceOptions as $option)
                        <option value="{{ $option }}" @selected(($filters['service'] ?? null) === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-country" class="mb-1 block text-xs font-medium text-slate-500">Country</label>
                <select id="f-country" name="country" class="{{ $input }}">
                    <option value="">Any country</option>
                    @foreach ($countryOptions as $option)
                        <option value="{{ $option }}" @selected(($filters['country'] ?? null) === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-assigned" class="mb-1 block text-xs font-medium text-slate-500">Assigned to</label>
                <select id="f-assigned" name="assigned_to" class="{{ $input }}">
                    <option value="">Anyone</option>
                    <option value="unassigned" @selected(($filters['assigned_to'] ?? null) === 'unassigned')>Unassigned</option>
                    @foreach ($assignees as $assignee)
                        <option value="{{ $assignee->id }}" @selected((string) ($filters['assigned_to'] ?? '') === (string) $assignee->id)>{{ $assignee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-source" class="mb-1 block text-xs font-medium text-slate-500">Source</label>
                <select id="f-source" name="source" class="{{ $input }}">
                    <option value="">Any source</option>
                    @foreach ($sources as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['source'] ?? null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-from" class="mb-1 block text-xs font-medium text-slate-500">From</label>
                <input id="f-from" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="{{ $input }}">
            </div>
            <div>
                <label for="f-to" class="mb-1 block text-xs font-medium text-slate-500">To</label>
                <input id="f-to" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="{{ $input }}">
            </div>
            <div class="flex items-end gap-2 sm:col-span-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    Apply filters
                </button>
                @if ($hasFilters)
                    <a href="{{ route('admin.leads.index', array_filter(['trashed' => request('trashed')])) }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-100">Clear</a>
                @endif
            </div>
        </form>
    </details>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[56rem] text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3"><span class="sr-only">Read</span></th>
                    <th class="px-4 py-3"><a href="{{ $sortUrl('name') }}" class="hover:text-slate-800">Lead {{ $sortIcon('name') }}</a></th>
                    <th class="px-4 py-3"><a href="{{ $sortUrl('company') }}" class="hover:text-slate-800">Company {{ $sortIcon('company') }}</a></th>
                    <th class="px-4 py-3">Service</th>
                    <th class="px-4 py-3"><a href="{{ $sortUrl('country') }}" class="hover:text-slate-800">Country {{ $sortIcon('country') }}</a></th>
                    <th class="px-4 py-3"><a href="{{ $sortUrl('status') }}" class="hover:text-slate-800">Status {{ $sortIcon('status') }}</a></th>
                    <th class="px-4 py-3">Assigned</th>
                    <th class="px-4 py-3"><a href="{{ $sortUrl('created_at') }}" class="hover:text-slate-800">Received {{ $sortIcon('created_at') }}</a></th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($leads as $lead)
                    <tr class="transition hover:bg-slate-50 {{ ! $lead->is_read ? 'bg-fuchsia-50/40' : '' }}">
                        <td class="px-4 py-3">
                            @if ($lead->is_read)
                                <x-admin.icon name="check-circle" class="h-4 w-4 text-emerald-500" />
                            @else
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-fuchsia-500 ring-4 ring-fuchsia-100" title="Unread"></span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.leads.show', $lead) }}" class="{{ ! $lead->is_read ? 'font-semibold text-slate-900' : 'font-medium text-slate-700' }} hover:text-fuchsia-700">{{ $lead->name }}</a>
                            <div class="text-xs text-slate-400">{{ $lead->reference() }} · {{ $lead->email ?? $lead->phone ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $lead->company ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $lead->service ?? '—' }}
                            <div class="text-xs text-slate-400">{{ $lead->sourceLabel() }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $lead->country ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $lead->status->badgeClasses() }}">{{ $lead->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $lead->assignee?->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $lead->created_at->timezone(config('app.timezone'))->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.leads.show', $lead) }}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-600 transition hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700">
                                    <x-admin.icon name="eye" class="h-3.5 w-3.5" /> View
                                </a>
                                @if ($trashed)
                                    @can('restore', $lead)
                                        <form action="{{ route('admin.leads.restore', $lead) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-emerald-200 px-2.5 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-50">Restore</button>
                                        </form>
                                    @endcan
                                @else
                                    @can('delete', $lead)
                                        <x-admin.delete-button :action="route('admin.leads.destroy', $lead)" confirm="Move this lead to the trash?" />
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-slate-400">
                            {{ $hasFilters || $activeStatus ? 'No leads match these filters.' : ($trashed ? 'The trash is empty.' : 'No leads yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-admin.pagination :paginator="$leads" />
@endsection
