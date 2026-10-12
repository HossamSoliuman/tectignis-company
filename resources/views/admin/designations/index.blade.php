@extends('layouts.admin')

@section('title', 'Designations')

@section('content')
    @php
        // Sorting links keep every other parameter (spec §28.4).
        $sortUrl = function (string $column) use ($sort, $direction) {
            $nextDirection = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';

            return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection, 'page' => null]);
        };
        $sortIcon = fn (string $column): string => $sort === $column ? ($direction === 'desc' ? '↓' : '↑') : '';
        $statusTabs = [[null, 'All'], ['active', 'Active'], ['inactive', 'Inactive']];
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="tag" class="h-5 w-5 text-fuchsia-600" />
            Designations
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $designations->total() }}</span>
        </h2>
        <div class="flex flex-wrap items-center gap-2">
            <x-admin.search-form placeholder="Search designations…" />
            <a href="{{ route('admin.designations.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Designation
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <nav class="-mb-px flex gap-1 overflow-x-auto border-b border-slate-200 px-3" aria-label="Designation status">
            @foreach ($statusTabs as [$statusValue, $statusLabel])
                @php $isCurrent = $activeStatus === $statusValue; @endphp
                <a href="{{ request()->fullUrlWithQuery(['status' => $statusValue, 'page' => null]) }}"
                    class="inline-flex shrink-0 items-center border-b-2 px-3 py-3.5 text-sm font-medium transition {{ $isCurrent ? 'border-fuchsia-600 text-fuchsia-700' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-800' }}"
                    @if ($isCurrent) aria-current="page" @endif>
                    {{ $statusLabel }}
                </a>
            @endforeach
        </nav>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[44rem] text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('sort_order') }}" class="hover:text-slate-800">Order {{ $sortIcon('sort_order') }}</a></th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('name') }}" class="hover:text-slate-800">Designation {{ $sortIcon('name') }}</a></th>
                        <th class="px-4 py-3">Applications</th>
                        <th class="px-4 py-3"><a href="{{ $sortUrl('is_active') }}" class="hover:text-slate-800">Status {{ $sortIcon('is_active') }}</a></th>
                        <th class="px-4 py-3">Last updated</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($designations as $designation)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3 font-mono text-slate-500">{{ $designation->sort_order }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-800">{{ $designation->name }}</div>
                                @if ($designation->department)
                                    <div class="text-xs text-slate-500">{{ $designation->department }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($designation->applications_count)
                                    <a href="{{ route('admin.job-applications.index', ['designation_id' => $designation->id]) }}" class="font-medium text-fuchsia-600 hover:underline">{{ number_format($designation->applications_count) }}</a>
                                @else
                                    <span class="text-slate-300">0</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('admin.designations.toggle', $designation) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $designation->is_active ? 'Deactivate' : 'Activate' }}" aria-label="{{ ($designation->is_active ? 'Deactivate ' : 'Activate ').$designation->name }}">
                                        <x-admin.status-badge :active="$designation->is_active" />
                                    </button>
                                </form>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-slate-500">
                                {{ $designation->updated_at?->timezone(config('app.timezone'))->format('M d, Y') }}
                                @if ($designation->updater)
                                    <div>{{ $designation->updater->name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <x-admin.edit-link :href="route('admin.designations.edit', $designation)" />
                                    @unless ($designation->applications_count)
                                        <x-admin.delete-button :action="route('admin.designations.destroy', $designation)" confirm="Delete this designation?" />
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-slate-400">No designations match.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.pagination :paginator="$designations" />
@endsection
