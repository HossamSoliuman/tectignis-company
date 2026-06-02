@extends('layouts.admin')

@section('title', 'Solutions')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="puzzle" class="h-5 w-5 text-fuchsia-600" />
            Solutions
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $solutions->count() }}</span>
        </h2>
        <a href="{{ route('admin.solutions.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
            <x-admin.icon name="plus" class="h-4 w-4" /> New Solution
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Icon</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Custom Page</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($solutions as $solution)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3">
                            @if ($solution->icon)
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                                    <i class="{{ $solution->icon }}"></i>
                                </span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $solution->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $solution->slug }}</td>
                        <td class="px-4 py-3">
                            @if ($solution->body)
                                <span class="rounded-full bg-fuchsia-50 px-2 py-0.5 text-xs font-medium text-fuchsia-700">HTML</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $solution->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$solution->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.solutions.edit', $solution)" />
                                <x-admin.delete-button :action="route('admin.solutions.destroy', $solution)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-400">No solutions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
