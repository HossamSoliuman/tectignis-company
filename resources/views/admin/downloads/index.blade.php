@extends('layouts.admin')

@section('title', 'Downloads')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="download" class="h-5 w-5 text-fuchsia-600" />
            Downloads
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $downloads->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#downloads-table" placeholder="Search downloads…" />
            <a href="{{ route('admin.downloads.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Download
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="downloads-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">File</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($downloads as $download)
                    <tr class="transition hover:bg-slate-50">
                        <td class="max-w-xs truncate px-4 py-3 font-medium text-slate-800">{{ $download->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $download->categoryLabel() }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold uppercase text-slate-600">{{ $download->file_type }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($download->file)
                                <a href="{{ asset('uploads/'.$download->file) }}" target="_blank" class="text-fuchsia-600 hover:underline">View</a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $download->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$download->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.downloads.edit', $download)" />
                                <x-admin.delete-button :action="route('admin.downloads.destroy', $download)" confirm="Delete this download?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-400">No downloads yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
