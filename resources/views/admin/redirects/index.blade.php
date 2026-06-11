@extends('layouts.admin')

@section('title', 'Redirects')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="switch-horizontal" class="h-5 w-5 text-fuchsia-600" />
            Redirects
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $redirects->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#redirects-table" placeholder="Search redirects…" />
            <a href="{{ route('admin.redirects.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Redirect
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="redirects-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">From</th>
                    <th class="px-4 py-3">To</th>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($redirects as $r)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $r->from_path }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-700">
                            <span class="inline-flex items-center gap-1">
                                <x-admin.icon name="arrow-left" class="h-3.5 w-3.5 rotate-180 text-slate-400" />
                                {{ $r->to_path }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $r->status_code ?? 301 }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$r->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.redirects.edit', $r)" />
                                <x-admin.delete-button :action="route('admin.redirects.destroy', $r)" confirm="Delete this redirect?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400">No redirects configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
