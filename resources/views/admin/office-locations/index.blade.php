@extends('layouts.admin')

@section('title', 'Office Locations')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="office" class="h-5 w-5 text-fuchsia-600" />
            Office Locations
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $locations->count() }}</span>
        </h2>
        <a href="{{ route('admin.office-locations.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
            <x-admin.icon name="plus" class="h-4 w-4" /> New Location
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Region</th>
                    <th class="px-4 py-3">City</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($locations as $location)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $location->region === 'global' ? 'bg-indigo-100 text-indigo-700' : 'bg-fuchsia-100 text-fuchsia-700' }}">{{ ucfirst($location->region) }}</span>
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $location->city }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $location->type }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $location->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$location->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.office-locations.edit', $location)" />
                                <x-admin.delete-button :action="route('admin.office-locations.destroy', $location)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No locations yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
