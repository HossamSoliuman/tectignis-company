@extends('layouts.admin')

@section('title', 'Leads')

@section('content')
    @php
        $canExport = auth()->user()->can('export', \App\Models\Lead::class);
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $activeStatus = $filters['status'] ?? null;
        $statusTabs = collect($statuses)
            ->map(fn ($status): array => [$status->value, $status->label(), $statusCounts[$status->value] ?? 0])
            ->prepend([null, 'All', $statusCounts->sum()]);
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

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        {{-- Toggles the filter panel below without JavaScript --}}
        <input type="checkbox" id="lead-filters-toggle" class="peer sr-only" @checked($hasFilters)>

        {{-- Pipeline: one tab per status with live counts for the current filters --}}
        <div class="flex items-center gap-3 border-b border-slate-200 px-2">
            <nav class="-mb-px flex flex-1 gap-1 overflow-x-auto" aria-label="Lead status">
                @foreach ($statusTabs as [$statusValue, $statusLabel, $count])
                    @php $isCurrent = $activeStatus === $statusValue; @endphp
                    <a href="{{ request()->fullUrlWithQuery(['status' => $statusValue, 'page' => null]) }}"
                        class="inline-flex shrink-0 items-center gap-2 border-b-2 px-3 py-3 text-sm font-medium transition {{ $isCurrent ? 'border-fuchsia-600 text-fuchsia-700' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-800' }}"
                        @if ($isCurrent) aria-current="page" @endif>
                        {{ $statusLabel }}
                        <span class="rounded-full px-1.5 py-0.5 text-[11px] font-semibold leading-none {{ $isCurrent ? 'bg-fuchsia-100 text-fuchsia-700' : ($count ? 'bg-slate-100 text-slate-600' : 'bg-slate-50 text-slate-400') }}">{{ number_format($count) }}</span>
                    </a>
                @endforeach
            </nav>
            <label for="lead-filters-toggle"
                class="mr-2 inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-800">
                <x-admin.icon name="search" class="h-4 w-4 text-slate-400" /> Filters
                @if ($hasFilters)
                    <span class="h-2 w-2 rounded-full bg-fuchsia-500" title="Filters active"></span>
                @endif
            </label>
        </div>

        <form method="GET" action="{{ route('admin.leads.index') }}" class="hidden grid-cols-1 gap-3 border-b border-slate-200 bg-slate-50/60 p-4 peer-checked:grid sm:grid-cols-2 lg:grid-cols-6">
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
            <div class="flex items-center justify-end gap-2 sm:col-span-2 lg:col-span-6">
                @if ($hasFilters)
                    <a href="{{ route('admin.leads.index', array_filter(['trashed' => request('trashed')])) }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-100">Clear</a>
                @endif
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    Apply filters
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[56rem] text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="w-8 py-3 pl-4 pr-0"><span class="sr-only">Read</span></th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('name') }}" class="hover:text-slate-800">Lead {{ $sortIcon('name') }}</a></th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('company') }}" class="hover:text-slate-800">Company {{ $sortIcon('company') }}</a></th>
                        <th class="px-4 py-3">Service / Source</th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('country') }}" class="hover:text-slate-800">Country {{ $sortIcon('country') }}</a></th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('status') }}" class="hover:text-slate-800">Status {{ $sortIcon('status') }}</a></th>
                        <th class="px-4 py-3">Assigned</th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('created_at') }}" class="hover:text-slate-800">Received {{ $sortIcon('created_at') }}</a></th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leads as $lead)
                        <tr class="transition hover:bg-slate-50">
                            <td class="py-3 pl-4 pr-0">
                                @unless ($lead->is_read)
                                    <span class="block h-2 w-2 rounded-full bg-fuchsia-500" title="Unread"></span>
                                    <span class="sr-only">Unread</span>
                                @endunless
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="{{ ! $lead->is_read ? 'font-semibold text-slate-900' : 'font-medium text-slate-700' }} hover:text-fuchsia-700">{{ $lead->name }}</a>
                                <div class="text-xs text-slate-400">{{ $lead->reference() }} · {{ $lead->email ?? $lead->phone ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $lead->company ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                @if ($lead->service)
                                    {{ $lead->service }}
                                    <div class="text-xs text-slate-400">{{ $lead->sourceLabel() }}</div>
                                @else
                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $lead->sourceLabel() }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $lead->country ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $lead->status->badgeClasses() }}">{{ $lead->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $lead->assignee?->name ?? '—' }}</td>
                            @php $receivedAt = $lead->created_at->timezone(config('app.timezone')); @endphp
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">
                                {{ $receivedAt->format('M d, Y') }}
                                <div class="text-xs text-slate-400">{{ $receivedAt->format('H:i') }}</div>
                            </td>
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
    </div>

    <x-admin.pagination :paginator="$leads" />
@endsection
