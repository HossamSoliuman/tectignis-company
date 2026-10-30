@extends('layouts.admin')

@section('title', 'Case Studies')

@php
    $activeStatus = request('status');
    $statusTabs = collect(\App\Enums\CaseStudyStatus::cases())
        ->map(fn ($status): array => [$status->value, $status->label(), $statusCounts[$status->value] ?? 0])
        ->prepend([null, 'All', $statusCounts->sum()]);
    $input = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
@endphp

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="photograph" class="h-5 w-5 text-fuchsia-600" />
            Case Studies
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $caseStudies->total() }}</span>
        </h2>
        <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
            <x-admin.search-form placeholder="Search projects…" />
            <a href="{{ route('admin.case-study-categories.index') }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                <x-admin.icon name="folder" class="h-4 w-4" /> Categories
            </a>
            <a href="{{ route('admin.case-studies.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Case Study
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-3">
            <nav class="-mb-px flex gap-1 overflow-x-auto" aria-label="Publication status">
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
        </div>

        <form method="GET" action="{{ route('admin.case-studies.index') }}" class="grid grid-cols-1 gap-3 border-b border-slate-200 bg-slate-50/60 p-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (['q', 'status', 'per_page'] as $keep)
                @if (filled(request($keep)))
                    <input type="hidden" name="{{ $keep }}" value="{{ request($keep) }}">
                @endif
            @endforeach
            <div>
                <label for="f-category" class="mb-1 block text-xs font-medium text-slate-500">Category</label>
                <select id="f-category" name="category" class="{{ $input }}">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) request('category') === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-stage" class="mb-1 block text-xs font-medium text-slate-500">Project stage</label>
                <select id="f-stage" name="project_status" class="{{ $input }}">
                    <option value="">All stages</option>
                    @foreach (\App\Enums\ProjectStatus::options() as $value => $text)
                        <option value="{{ $value }}" @selected(request('project_status') === $value)>{{ $text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end pb-2">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="checkbox" name="featured" value="1" @checked(request()->boolean('featured')) class="rounded border-slate-300">
                    Featured only
                </label>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">Apply</button>
                <a href="{{ route('admin.case-studies.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Reset</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[48rem] text-sm" id="case-studies-table">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Project</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Stage</th>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($caseStudies as $cs)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3">
                                @if ($cs->image)
                                    <img src="{{ asset('uploads/'.$cs->image) }}" alt="{{ $cs->coverAlt() }}"
                                        class="h-9 w-14 rounded-lg object-cover ring-1 ring-slate-100">
                                @else
                                    <span class="flex h-9 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-300">
                                        <x-admin.icon name="photograph" class="h-5 w-5" />
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 font-medium text-slate-800">
                                    {{ $cs->title }}
                                    @if ($cs->is_featured)
                                        <span class="rounded-full bg-fuchsia-100 px-2 py-0.5 text-[11px] font-semibold text-fuchsia-700">Featured</span>
                                    @endif
                                </div>
                                @if ($cs->client_name)
                                    <div class="text-xs text-slate-500">{{ $cs->client_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $cs->category?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $cs->project_status->label() }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $cs->sort_order }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $cs->status->badgeClasses() }}">{{ $cs->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.case-studies.show', $cs) }}" target="_blank" rel="noopener" title="Preview" aria-label="Preview {{ $cs->title }}"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                                        <x-admin.icon name="eye" class="h-4 w-4" />
                                    </a>
                                    <x-admin.edit-link :href="route('admin.case-studies.edit', $cs)" />
                                    <x-admin.delete-button :action="route('admin.case-studies.destroy', $cs)" confirm="Delete this case study?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-400">No case studies match.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.pagination :paginator="$caseStudies" />
@endsection
