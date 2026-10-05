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
        $activeFilterCount = collect(request()->only(['service', 'country', 'assigned_to', 'source', 'date_from', 'date_to']))->filter(fn ($v) => filled($v))->count();
        $initials = fn (string $name): string => Str::of($name)->squish()->explode(' ')->take(2)->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))->implode('');

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
            <x-admin.search-form placeholder="Search leads…" />
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
        <input type="checkbox" id="lead-filters-toggle" class="peer sr-only" @checked($activeFilterCount > 0)>

        {{-- Pipeline: one tab per status with live counts for the current filters --}}
        <div class="flex items-center gap-3 border-b border-slate-200 px-3">
            <nav class="-mb-px flex flex-1 gap-1 overflow-x-auto" aria-label="Lead status">
                @foreach ($statusTabs as [$statusValue, $statusLabel, $count])
                    @php $isCurrent = $activeStatus === $statusValue; @endphp
                    <a href="{{ request()->fullUrlWithQuery(['status' => $statusValue, 'page' => null]) }}"
                        class="inline-flex shrink-0 items-center gap-1.5 border-b-2 px-3 py-3.5 text-sm font-medium transition {{ $isCurrent ? 'border-fuchsia-600 text-fuchsia-700' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-800' }}"
                        @if ($isCurrent) aria-current="page" @endif>
                        {{ $statusLabel }}
                        <span class="min-w-5 rounded-full px-1.5 py-0.5 text-center text-[11px] font-semibold leading-none {{ $isCurrent ? 'bg-fuchsia-100 text-fuchsia-700' : ($count ? 'bg-slate-100 text-slate-600' : 'text-slate-300') }}">{{ number_format($count) }}</span>
                    </a>
                @endforeach
            </nav>
            <label for="lead-filters-toggle"
                class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition {{ $activeFilterCount ? 'border-fuchsia-200 bg-fuchsia-50 text-fuchsia-700 hover:bg-fuchsia-100' : 'border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <x-admin.icon name="funnel" class="h-4 w-4 {{ $activeFilterCount ? 'text-fuchsia-500' : 'text-slate-400' }}" /> Filters
                @if ($activeFilterCount)
                    <span class="rounded-full bg-fuchsia-600 px-1.5 py-0.5 text-[11px] font-semibold leading-none text-white">{{ $activeFilterCount }}</span>
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
            <table class="w-full min-w-[60rem] text-sm">
                <thead class="border-b border-slate-200 bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 pl-5 pr-4"><a href="{{ $sortUrl('name') }}" class="hover:text-slate-800">Lead {{ $sortIcon('name') }}</a></th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('company') }}" class="hover:text-slate-800">Company {{ $sortIcon('company') }}</a></th>
                        <th class="px-4 py-3">Source</th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('status') }}" class="hover:text-slate-800">Status {{ $sortIcon('status') }}</a></th>
                        <th class="px-4 py-3">Assigned</th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('created_at') }}" class="hover:text-slate-800">Received {{ $sortIcon('created_at') }}</a></th>
                        <th class="py-3 pl-4 pr-5"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leads as $lead)
                        @php
                            $receivedAt = $lead->created_at->timezone(config('app.timezone'));
                            $leadUrl = route('admin.leads.show', $lead);
                        @endphp
                        <tr class="transition {{ $lead->is_read ? 'hover:bg-slate-50' : 'bg-fuchsia-50/30 hover:bg-fuchsia-50/60' }}">
                            <td class="py-3 pl-5 pr-4">
                                <div class="flex items-center gap-3">
                                    <span class="relative shrink-0">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-semibold {{ $lead->is_read ? 'bg-slate-100 text-slate-500' : 'bg-fuchsia-100 text-fuchsia-700' }}">{{ $initials($lead->name) }}</span>
                                        @unless ($lead->is_read)
                                            <span class="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full bg-fuchsia-500 ring-2 ring-white" title="Unread"></span>
                                            <span class="sr-only">Unread</span>
                                        @endunless
                                    </span>
                                    <div class="min-w-0 max-w-64">
                                        <a href="{{ $leadUrl }}" class="block truncate {{ $lead->is_read ? 'font-medium text-slate-700' : 'font-semibold text-slate-900' }} hover:text-fuchsia-700">{{ $lead->name }}</a>
                                        <div class="truncate text-xs text-slate-500">
                                            <span class="font-mono text-slate-400">{{ $lead->reference() }}</span> · {{ $lead->email ?? $lead->phone ?? '—' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($lead->company || $lead->country)
                                    <div class="max-w-48 truncate text-slate-700">{{ $lead->company ?? $lead->country }}</div>
                                    @if ($lead->company && $lead->country)
                                        <div class="max-w-48 truncate text-xs text-slate-500">{{ $lead->country }}</div>
                                    @endif
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 whitespace-nowrap text-slate-700">
                                    <x-admin.icon name="{{ $lead->sourceEnum()?->icon() ?? 'inbox' }}" class="h-4 w-4 shrink-0 text-slate-400" />
                                    {{ $lead->sourceLabel() }}
                                </div>
                                @if ($lead->service)
                                    <div class="max-w-48 truncate pl-5.5 text-xs text-slate-500">{{ $lead->service }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-xs font-medium {{ $lead->status->badgeClasses() }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $lead->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($lead->assignee)
                                    <div class="flex items-center gap-2 whitespace-nowrap text-slate-700">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-semibold text-slate-600">{{ $initials($lead->assignee->name) }}</span>
                                        {{ $lead->assignee->name }}
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <time datetime="{{ $receivedAt->toIso8601String() }}" class="block text-slate-700">{{ $receivedAt->diffForHumans() }}</time>
                                <div class="text-xs text-slate-500">{{ $receivedAt->format('M d, Y · H:i') }}</div>
                            </td>
                            <td class="py-3 pl-4 pr-5">
                                <div class="flex items-center justify-end gap-0.5">
                                    @if ($trashed)
                                        @can('restore', $lead)
                                            <form action="{{ route('admin.leads.restore', $lead) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-emerald-200 px-2.5 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-50">Restore</button>
                                            </form>
                                        @endcan
                                    @endif
                                    <a href="{{ $leadUrl }}" title="View lead" aria-label="View {{ $lead->name }}"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-fuchsia-50 hover:text-fuchsia-700">
                                        <x-admin.icon name="eye" class="h-4 w-4" />
                                    </a>
                                    @if (! $trashed)
                                        @can('delete', $lead)
                                            <x-admin.delete-button :action="route('admin.leads.destroy', $lead)" confirm="Move this lead to the trash?" :icon-only="true" />
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <x-admin.icon name="{{ $trashed ? 'trash' : 'inbox' }}" class="mx-auto h-8 w-8 text-slate-300" />
                                <p class="mt-2 text-sm font-medium text-slate-600">
                                    {{ $hasFilters || $activeStatus ? 'No leads match these filters.' : ($trashed ? 'The trash is empty.' : 'No leads yet.') }}
                                </p>
                                @if ($hasFilters || $activeStatus)
                                    <a href="{{ route('admin.leads.index', array_filter(['trashed' => request('trashed')])) }}" class="mt-1 inline-block text-sm font-medium text-fuchsia-600 hover:text-fuchsia-700">Clear filters</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.pagination :paginator="$leads" />
@endsection
