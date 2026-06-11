@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="document-duplicate" class="h-5 w-5 text-fuchsia-600" />
            Pages
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $pages->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#pages-table" placeholder="Search pages…" />
            <a href="{{ route('admin.pages.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Page
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="pages-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">URL</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pages as $page)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $page->title }}</td>
                        <td class="px-4 py-3 font-mono text-slate-500">/{{ $page->slug }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $page->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$page->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.pages.edit', $page)" />
                                <x-admin.delete-button :action="route('admin.pages.destroy', $page)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400">No pages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
