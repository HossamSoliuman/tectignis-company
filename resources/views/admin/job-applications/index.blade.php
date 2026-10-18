@extends('layouts.admin')

@section('title', 'Applications')

@section('content')
    @php
        $canExport = auth()->user()->can('export', \App\Models\JobApplication::class);
        $activeStatus = $filters['status'] ?? null;
        $statusTabs = collect($statuses)
            ->map(fn ($status): array => [$status->value, $status->label(), $statusCounts[$status->value] ?? 0])
            ->prepend([null, 'All', $statusCounts->sum()]);
        $hasFilters = collect(request()->except(['page', 'per_page', 'sort', 'direction', 'status', 'archived']))->filter(fn ($v) => filled($v))->isNotEmpty();
        $activeFilterCount = collect(request()->only(['job_opening_id', 'designation_id', 'experience', 'location', 'assigned_to', 'date_from', 'date_to']))->filter(fn ($v) => filled($v))->count();
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
            <x-admin.icon name="briefcase" class="h-5 w-5 text-fuchsia-600" />
            {{ $archived ? 'Archived Applications' : 'Applications' }}
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $applications->total() }}</span>
        </h2>
        <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
            <x-admin.search-form placeholder="Search candidates…" />
            @if ($canExport)
                <a href="{{ route('admin.job-applications.export', request()->except(['page', 'per_page'])) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700">
                    <x-admin.icon name="download" class="h-4 w-4" /> Export CSV
                </a>
            @endif
            <a href="{{ $archived ? route('admin.job-applications.index') : route('admin.job-applications.index', ['archived' => 1]) }}"
                class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                <x-admin.icon name="{{ $archived ? 'arrow-left' : 'folder' }}" class="h-4 w-4" /> {{ $archived ? 'Back to applications' : 'Archive' }}
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        {{-- Toggles the filter panel below without JavaScript --}}
        <input type="checkbox" id="application-filters-toggle" class="peer sr-only" @checked($activeFilterCount > 0)>

        {{-- Pipeline: one tab per status with live counts for the current filters --}}
        <div class="flex items-center gap-3 border-b border-slate-200 px-3">
            <nav class="-mb-px flex flex-1 gap-1 overflow-x-auto" aria-label="Application status">
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
            <label for="application-filters-toggle"
                class="inline-flex shrink-0 cursor-pointer items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition {{ $activeFilterCount ? 'border-fuchsia-200 bg-fuchsia-50 text-fuchsia-700 hover:bg-fuchsia-100' : 'border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800' }}">
                <x-admin.icon name="funnel" class="h-4 w-4 {{ $activeFilterCount ? 'text-fuchsia-500' : 'text-slate-400' }}" /> Filters
                @if ($activeFilterCount)
                    <span class="rounded-full bg-fuchsia-600 px-1.5 py-0.5 text-[11px] font-semibold leading-none text-white">{{ $activeFilterCount }}</span>
                @endif
            </label>
        </div>

        <form method="GET" action="{{ route('admin.job-applications.index') }}" class="hidden grid-cols-1 gap-3 border-b border-slate-200 bg-slate-50/60 p-4 peer-checked:grid sm:grid-cols-2 lg:grid-cols-4">
            @foreach (['q', 'status', 'sort', 'direction', 'per_page', 'archived'] as $keep)
                @if (filled(request($keep)))
                    <input type="hidden" name="{{ $keep }}" value="{{ request($keep) }}">
                @endif
            @endforeach

            <div>
                <label for="f-job" class="mb-1 block text-xs font-medium text-slate-500">Job</label>
                <select id="f-job" name="job_opening_id" class="{{ $input }}">
                    <option value="">Any job</option>
                    <option value="general" @selected(($filters['job_opening_id'] ?? null) === 'general')>General applications</option>
                    @foreach ($jobOptions as $job)
                        <option value="{{ $job->id }}" @selected((string) ($filters['job_opening_id'] ?? '') === (string) $job->id)>{{ $job->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-designation" class="mb-1 block text-xs font-medium text-slate-500">Designation</label>
                <select id="f-designation" name="designation_id" class="{{ $input }}">
                    <option value="">Any designation</option>
                    @foreach ($designationOptions as $designation)
                        <option value="{{ $designation->id }}" @selected((string) ($filters['designation_id'] ?? '') === (string) $designation->id)>{{ $designation->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-experience" class="mb-1 block text-xs font-medium text-slate-500">Experience</label>
                <select id="f-experience" name="experience" class="{{ $input }}">
                    <option value="">Any experience</option>
                    @foreach ($experiences as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['experience'] ?? null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-location" class="mb-1 block text-xs font-medium text-slate-500">Location</label>
                <input id="f-location" type="text" name="location" value="{{ $filters['location'] ?? '' }}" maxlength="100" placeholder="e.g. Mumbai" class="{{ $input }}">
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
                <label for="f-from" class="mb-1 block text-xs font-medium text-slate-500">From</label>
                <input id="f-from" type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="{{ $input }}">
            </div>
            <div>
                <label for="f-to" class="mb-1 block text-xs font-medium text-slate-500">To</label>
                <input id="f-to" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="{{ $input }}">
            </div>
            <div class="flex items-end justify-end gap-2">
                @if ($hasFilters)
                    <a href="{{ route('admin.job-applications.index', array_filter(['archived' => request('archived')])) }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-500 hover:bg-slate-100">Clear</a>
                @endif
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    Apply filters
                </button>
            </div>
        </form>

        {{-- Columns fold away as the card narrows instead of scrolling sideways; folded details move into the candidate cell --}}
        <div class="@container overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50/70 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3 pl-5 pr-4"><a href="{{ $sortUrl('name') }}" class="hover:text-slate-800">Candidate {{ $sortIcon('name') }}</a></th>
                        <th class="hidden px-4 py-3 @4xl:table-cell"><a href="{{ $sortUrl('job_title') }}" class="hover:text-slate-800">Applied for {{ $sortIcon('job_title') }}</a></th>
                        <th class="hidden px-4 py-3 @6xl:table-cell"><a href="{{ $sortUrl('experience') }}" class="hover:text-slate-800">Experience {{ $sortIcon('experience') }}</a></th>
                        <th class="hidden px-4 py-3 @2xl:table-cell"><a href="{{ $sortUrl('status') }}" class="hover:text-slate-800">Status {{ $sortIcon('status') }}</a></th>
                        <th class="hidden px-4 py-3 @5xl:table-cell">Assigned</th>
                        <th class="hidden px-4 py-3 @2xl:table-cell"><a href="{{ $sortUrl('created_at') }}" class="hover:text-slate-800">Received {{ $sortIcon('created_at') }}</a></th>
                        <th class="py-3 pl-4 pr-5"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($applications as $application)
                        @php
                            $receivedAt = $application->created_at->timezone(config('app.timezone'));
                            $applicationUrl = route('admin.job-applications.show', $application);
                        @endphp
                        <tr class="transition {{ $application->is_read ? 'hover:bg-slate-50' : 'bg-fuchsia-50/30 hover:bg-fuchsia-50/60' }}">
                            <td class="py-3 pl-5 pr-4">
                                <div class="flex items-center gap-3">
                                    <span class="relative shrink-0">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-semibold {{ $application->is_read ? 'bg-slate-100 text-slate-500' : 'bg-fuchsia-100 text-fuchsia-700' }}">{{ $initials($application->name) }}</span>
                                        @unless ($application->is_read)
                                            <span class="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full bg-fuchsia-500 ring-2 ring-white" title="Unread"></span>
                                            <span class="sr-only">Unread</span>
                                        @endunless
                                    </span>
                                    <div class="min-w-0 max-w-64">
                                        <a href="{{ $applicationUrl }}" class="block truncate {{ $application->is_read ? 'font-medium text-slate-700' : 'font-semibold text-slate-900' }} hover:text-fuchsia-700">{{ $application->name }}</a>
                                        <div class="truncate text-xs text-slate-500">
                                            <span class="font-mono text-slate-400">{{ $application->reference() }}</span> · {{ $application->email }}
                                        </div>
                                        <div class="truncate text-xs text-slate-500 @4xl:hidden">{{ $application->appliedFor() }}</div>
                                        @if ($application->experienceLabel() || $application->location)
                                            <div class="truncate text-xs text-slate-500 @6xl:hidden">{{ collect([$application->experienceLabel(), $application->location])->filter()->implode(' · ') }}</div>
                                        @endif
                                        <div class="mt-1 flex flex-wrap items-center gap-2 @2xl:hidden">
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-xs font-medium {{ $application->status->badgeClasses() }}">
                                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                                {{ $application->status->label() }}
                                            </span>
                                            <time datetime="{{ $receivedAt->toIso8601String() }}" class="text-xs text-slate-500">{{ $receivedAt->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden px-4 py-3 @4xl:table-cell">
                                <div class="max-w-56 truncate text-slate-700">{{ $application->appliedFor() }}</div>
                                @if ($application->designation_name)
                                    <div class="max-w-56 truncate text-xs text-slate-500">{{ $application->designation_name }}</div>
                                @endif
                            </td>
                            <td class="hidden px-4 py-3 @6xl:table-cell">
                                <div class="whitespace-nowrap text-slate-700">{{ $application->experienceLabel() ?? '—' }}</div>
                                @if ($application->location)
                                    <div class="max-w-40 truncate text-xs text-slate-500">{{ $application->location }}</div>
                                @endif
                            </td>
                            <td class="hidden px-4 py-3 @2xl:table-cell">
                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium {{ $application->status->badgeClasses() }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $application->status->label() }}
                                </span>
                            </td>
                            <td class="hidden px-4 py-3 @5xl:table-cell">
                                @if ($application->assignee)
                                    <div class="flex items-center gap-2 whitespace-nowrap text-slate-700">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-semibold text-slate-600">{{ $initials($application->assignee->name) }}</span>
                                        {{ $application->assignee->name }}
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="hidden whitespace-nowrap px-4 py-3 @2xl:table-cell">
                                <time datetime="{{ $receivedAt->toIso8601String() }}" class="block text-slate-700">{{ $receivedAt->diffForHumans() }}</time>
                                <div class="text-xs text-slate-500">{{ $receivedAt->format('M d, Y · H:i') }}</div>
                            </td>
                            <td class="py-3 pl-4 pr-5">
                                <div class="flex items-center justify-end gap-0.5">
                                    <a href="{{ $applicationUrl }}" title="View application" aria-label="View {{ $application->name }}"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-fuchsia-50 hover:text-fuchsia-700">
                                        <x-admin.icon name="eye" class="h-4 w-4" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <x-admin.icon name="{{ $archived ? 'folder' : 'briefcase' }}" class="mx-auto h-8 w-8 text-slate-300" />
                                <p class="mt-2 text-sm font-medium text-slate-600">
                                    {{ $hasFilters || $activeStatus ? 'No applications match these filters.' : ($archived ? 'The archive is empty.' : 'No applications yet.') }}
                                </p>
                                @if ($hasFilters || $activeStatus)
                                    <a href="{{ route('admin.job-applications.index', array_filter(['archived' => request('archived')])) }}" class="mt-1 inline-block text-sm font-medium text-fuchsia-600 hover:text-fuchsia-700">Clear filters</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.pagination :paginator="$applications" />
@endsection
