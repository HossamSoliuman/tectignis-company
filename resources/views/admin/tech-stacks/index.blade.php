@extends('layouts.admin')

@section('title', 'Tech Stack')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="chip" class="h-5 w-5 text-fuchsia-600" />
            Technology Stack
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $techStacks->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#tech-stacks-table" placeholder="Search technologies…" />
            <a href="{{ route('admin.tech-stacks.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Technology
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="tech-stacks-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Logo</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3">On Home</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($techStacks as $techStack)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3">
                            @if ($techStack->logo)
                                <img src="{{ asset('uploads/'.$techStack->logo) }}" alt="{{ $techStack->name }}"
                                    class="h-9 w-9 rounded-lg object-contain ring-1 ring-slate-100">
                            @else
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-300">
                                    <x-admin.icon name="photograph" class="h-5 w-5" />
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $techStack->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $techStack->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$techStack->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$techStack->show_on_home" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.tech-stacks.edit', $techStack)" />
                                <x-admin.delete-button :action="route('admin.tech-stacks.destroy', $techStack)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No technologies yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
