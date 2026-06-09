@extends('layouts.admin')

@section('title', 'Global Advantages')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="globe" class="h-5 w-5 text-fuchsia-600" />
            Global Advantages
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $advantages->count() }}</span>
        </h2>
        <a href="{{ route('admin.global-advantages.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
            <x-admin.icon name="plus" class="h-4 w-4" /> New Advantage
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Tone</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($advantages as $advantage)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{!! $advantage->title !!}</td>
                        <td class="px-4 py-3 text-slate-500">{{ Str::limit($advantage->description, 60) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ ucfirst($advantage->tone) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $advantage->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$advantage->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.global-advantages.edit', $advantage)" />
                                <x-admin.delete-button :action="route('admin.global-advantages.destroy', $advantage)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No advantages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
