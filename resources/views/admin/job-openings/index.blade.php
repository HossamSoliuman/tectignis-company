@extends('layouts.admin')

@section('title', 'Job Openings')

@section('content')
    @php
        $statusTabs = collect(\App\Enums\JobOpeningStatus::cases())
            ->map(fn ($status): array => [$status, $status->label(), $statusCounts[$status->value] ?? 0])
            ->prepend([null, 'All', $statusCounts->sum()]);
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="briefcase" class="h-5 w-5 text-fuchsia-600" />
            Job Openings
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $jobOpenings->total() }}</span>
        </h2>
        <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
            <x-admin.search-form placeholder="Search openings…" />
            <a href="{{ route('admin.job-openings.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Job Opening
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <nav class="-mb-px flex gap-1 overflow-x-auto border-b border-slate-200 px-3" aria-label="Job opening status">
            @foreach ($statusTabs as [$status, $statusLabel, $count])
                @php $isCurrent = $activeStatus === $status; @endphp
                <a href="{{ request()->fullUrlWithQuery(['status' => $status?->value, 'page' => null]) }}"
                    class="inline-flex shrink-0 items-center gap-1.5 border-b-2 px-3 py-3.5 text-sm font-medium transition {{ $isCurrent ? 'border-fuchsia-600 text-fuchsia-700' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-800' }}"
                    @if ($isCurrent) aria-current="page" @endif>
                    {{ $statusLabel }}
                    <span class="min-w-5 rounded-full px-1.5 py-0.5 text-center text-[11px] font-semibold leading-none {{ $isCurrent ? 'bg-fuchsia-100 text-fuchsia-700' : ($count ? 'bg-slate-100 text-slate-600' : 'text-slate-300') }}">{{ number_format($count) }}</span>
                </a>
            @endforeach
        </nav>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[48rem] text-sm" id="job-openings-table">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Location</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Deadline</th>
                        <th class="px-4 py-3">Applications</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($jobOpenings as $job)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800">{{ $job->title }}</div>
                                <div class="text-xs text-slate-500">{{ $job->department ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $job->location ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $job->employment_type }}</td>
                            <td class="whitespace-nowrap px-4 py-3 {{ $job->deadlinePassed() ? 'text-rose-600' : 'text-slate-500' }}">
                                {{ $job->application_deadline?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($job->applications_count)
                                    <a href="{{ route('admin.job-applications.index', ['job_opening_id' => $job->id]) }}" class="font-medium text-fuchsia-600 hover:underline">{{ number_format($job->applications_count) }}</a>
                                @else
                                    <span class="text-slate-300">0</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-xs font-medium {{ $job->status->badgeClasses() }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $job->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($job->status !== \App\Enums\JobOpeningStatus::Draft)
                                        <a href="{{ route('careers.show', $job) }}" target="_blank" title="View public page" aria-label="View {{ $job->title }} on the website"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-fuchsia-50 hover:text-fuchsia-700">
                                            <x-admin.icon name="external-link" class="h-4 w-4" />
                                        </a>
                                    @endif
                                    <x-admin.edit-link :href="route('admin.job-openings.edit', $job)" />
                                    @unless ($job->applications_count)
                                        <x-admin.delete-button :action="route('admin.job-openings.destroy', $job)" confirm="Delete this job opening?" />
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-400">No job openings match.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.pagination :paginator="$jobOpenings" />
@endsection
