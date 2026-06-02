@extends('layouts.admin')

@section('title', 'Home Stats')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="chart-pie" class="h-5 w-5 text-fuchsia-600" />
            Home Stats
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $stats->count() }}</span>
        </h2>
        <a href="{{ route('admin.stats.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
            <x-admin.icon name="plus" class="h-4 w-4" /> New Stat
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Value</th>
                    <th class="px-4 py-3">Label</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($stats as $stat)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold text-fuchsia-600">{{ $stat->value }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $stat->label }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $stat->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$stat->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.stats.edit', $stat)" />
                                <x-admin.delete-button :action="route('admin.stats.destroy', $stat)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400">No stats yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
