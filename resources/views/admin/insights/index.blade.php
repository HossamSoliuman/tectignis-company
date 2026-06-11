@extends('layouts.admin')

@section('title', 'Technology Insights')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="light-bulb" class="h-5 w-5 text-fuchsia-600" />
            Technology Insights
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $insights->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#insights-table" placeholder="Search insights…" />
            <a href="{{ route('admin.insights.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Insight
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="insights-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Topic</th>
                    <th class="px-4 py-3">Published At</th>
                    <th class="px-4 py-3">Published</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($insights as $insight)
                    <tr class="transition hover:bg-slate-50">
                        <td class="max-w-sm truncate px-4 py-3 font-medium text-slate-800">{{ $insight->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $insight->topic ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $insight->published_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$insight->is_published" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.insights.edit', $insight)" />
                                <x-admin.delete-button :action="route('admin.insights.destroy', $insight)" confirm="Delete this insight?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400">No insights yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
