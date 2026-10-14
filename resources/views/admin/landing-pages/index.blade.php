@extends('layouts.admin')

@section('title', 'Landing Pages')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="trending-up" class="h-5 w-5 text-fuchsia-600" />
            Landing Pages
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $landingPages->total() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.search-form placeholder="Search landing pages…" />
            <a href="{{ route('admin.landing-pages.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Landing Page
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">URL</th>
                    <th class="px-4 py-3">Form Service</th>
                    <th class="px-4 py-3">Linked Service</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($landingPages as $landingPage)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $landingPage->title }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('landing-pages.show', $landingPage->slug) }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-1 text-fuchsia-600 hover:underline">
                                /lp/{{ $landingPage->slug }} <x-admin.icon name="external-link" class="h-3.5 w-3.5" />
                            </a>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $landingPage->enquiry_service }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $landingPage->service?->title ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$landingPage->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.landing-pages.edit', $landingPage)" />
                                <x-admin.delete-button :action="route('admin.landing-pages.destroy', $landingPage)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No landing pages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-admin.pagination :paginator="$landingPages" />
@endsection
