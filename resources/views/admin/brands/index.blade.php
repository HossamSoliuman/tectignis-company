@extends('layouts.admin')

@section('title', 'Brands')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="tag" class="h-5 w-5 text-fuchsia-600" />
            Brands
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $brands->count() }}</span>
        </h2>
        <a href="{{ route('admin.brands.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
            <x-admin.icon name="plus" class="h-4 w-4" /> New Brand
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Logo</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">URL</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($brands as $brand)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3">
                            @if ($brand->logo)
                                <img src="{{ asset('uploads/'.$brand->logo) }}" alt="{{ $brand->name }}"
                                    class="h-9 w-auto max-w-[7rem] object-contain">
                            @else
                                <span class="flex h-9 w-16 items-center justify-center rounded-lg bg-slate-100 text-slate-300">
                                    <x-admin.icon name="photograph" class="h-5 w-5" />
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $brand->name }}</td>
                        <td class="max-w-xs truncate px-4 py-3 text-slate-500">
                            @if ($brand->url)
                                <a href="{{ $brand->url }}" target="_blank" class="inline-flex items-center gap-1 text-fuchsia-600 hover:underline">
                                    <x-admin.icon name="link" class="h-3.5 w-3.5" /> {{ $brand->url }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $brand->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$brand->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.brands.edit', $brand)" />
                                <x-admin.delete-button :action="route('admin.brands.destroy', $brand)" confirm="Delete this brand?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No brands yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
